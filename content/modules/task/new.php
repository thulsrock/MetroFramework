<?php

	$target = new Target();
	try {
		$targetList = $target->list();
	} catch (Exception $e) {
		echo 'Non sono presenti obiettivi.';
	}

?>

<form method="post" enctype="multipart/form-data" action="">
	<div class='flex flex_column shadow round_border space_between nowrap border padding'>
		<div class="flex flex_row padding">
			<div class="flex flex_column small_right_margin">
				<label class='middle_blue_text bold'>Obiettivo</label>
				<select class='border padding' name="target" id="target" required>
					<?php foreach ( $targetList as $target ) { ?>
						<option class='padding' value=<?php echo esc( $target->code );?>><?php echo htmlentities( $target->code, ENT_QUOTES ); ?></option>	
					<?php }?>			
				</select>
			</div>
			<div class="flex flex_column small_right_margin">
				<label class='middle_blue_text bold'>Codice attività</label>
				<input class='padding' type="text" name="code" id="code" required />
			</div>
			<div class="flex flex_column full_width">
				<label class='middle_blue_text bold'>Nome</label>
				<input class='padding' type="text" name="name" id="name" required />
			</div>
		</div>
		<div class="flex flex_column padding">
			<label class='middle_blue_text bold'>Descrizione</label>
			<textarea class='padding' name="description" id="description" rows="8" ></textarea>
		</div>
		<div class="flex flex_row padding">
			<div class="flex flex_column small_right_margin fair_grow">
				<label class='middle_blue_text bold'>Data di inizio</label>
				<input class='padding' style="width: auto;" type="date" name="startDate" id="startDate" value="<?php echo DEFAULT_START_DATE; ?>" />
			</div>	
			<div class="flex flex_column fair_grow small_right_margin">
				<label class='middle_blue_text bold'>Data di raggiungimento</label>
				<input class='padding' style="width: auto;" type="date" name="endDate" id="endDate" value="" />
			</div>
			<div class="flex flex_column">
				<label class='middle_blue_text bold'>Risultato atteso</label>
				<input class='padding' style="width: auto;" type="number" name="attendedResult" id="attendedResult" value="100" required />
			</div>
		</div>
			
		<div class='flex wrap align_center'>
			<?php $this->buttonFormSend('task', 'new'); ?>
		</div>	
	</div>
</form>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
	  .custom-combobox {
	  	text-align: left;
	    position: relative;
	    display: inline-block;
	  }
	  .custom-combobox-toggle {
	    position: absolute;
	    top: 0;
	    bottom: 0;
	    margin-left: -1px;
	    padding: 0;
	  }
	  .custom-combobox-input {
	    margin: 0;
	    padding: 5px 10px;
	  }
</style>
  
<script>
  $( function() {
	    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span style='margin-right: 30px;'>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input class='padding' onchange='showDep(this.value)'>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "ui-widget-content ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            classes: {
              "ui-tooltip": "ui-state-highlight"
            }
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Mostra tutti" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .on( "mousedown", function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .on( "click", function() {
            input.trigger( "focus" );
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " non ha riscontri" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
 
    $( "#target" ).combobox();
    $( "#toggle" ).on( "click", function() {
      $( "#target" ).toggle();
    });
  } );
</script>