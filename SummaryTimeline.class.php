<?php
/**
 * The SummaryTimeline extension generates a graphic representation
 * of an EVA summary timeline within MediaWiki.
 * 
 * Documentation: https://github.com/darenwelsh/SummaryTimeline
 * Support:       https://github.com/darenwelsh/SummaryTimeline
 * Source code:   https://github.com/darenwelsh/SummaryTimeline
 *
 * @file SummaryTimeline.class.php
 * @addtogroup Extensions
 * @author Daren Welsh
 * @copyright © 2014 by Daren Welsh
 * @licence GNU GPL v3+
 */

class SummaryTimeline
{

	static function setup ( &$parser ) {

		$parser->setFunctionHook(
			// name of parser function
			// same as $magicWords value set in MasonryMainPage.i18n.php 
			'summary-timeline', 
			array(
				'SummaryTimeline',  // class to call function from
				'renderSummaryTimeline' // function to call within that class
			),
			SFH_OBJECT_ARGS // defines format of how data is passed to function
		);

		return true;

	}

	static function renderSummaryTimeline ( &$parser, $frame, $args ) {
		// self::addCSS(); // adds the CSS files 

		//The raw input looks like this:
		// {{#summary-timeline: title=US EVA 100
		// 	| duration = 6:30
		// 	| row=EV1 
		// 	| 30 Egress 
		// 	| 40 SSRMS Setup## blue
		// 	| 1:30 FHRC Release
		// 	 ESP-2 FHRC
		// 	| 20 Maneuver from ESP-2 to S1
		// 	| 90 FHRC Install
		// 	| 45 SSRMS Cleanup
		// 	| 30 Get-Aheads (make this auto-fill based on EVA duration)
		// 	| 45 Ingress
		// 	| row=EV2
		// 	| 30 Egress
		// 	| 40 FHRC Prep
		// 	| 90 FHRC Release
		// 	| 20 MMOD Inspection
		// 	| 110 FHRC Install
		// 	| 10 Get-Aheads
		// 	| 45 Ingress
		//  }}

		//The $args array looks like this:
		//	[0] => 'title=Title of EVA'
		//  [1] => 'duration = 6:30'
		//  [2] => 'row=EV1'
		//  [3] => '30 Egress'
		//  and so on ... (everything divided by | )

		//Run extractOptions on $args
		$options = self::extractOptions( $frame, $args );

		//Define the main output
		$text = "<table class=''>" .

	        //This contains the heading of the masonry block (a wiki link to whatever is passed)
	        "<tr><th>[[" . $options['title'] . "]]</th></tr>" .
			
			//This contains the body of the masonry block
			//Wiki code like links can be include; templates and wiki tables cannot
			"<tr><td>"
	         . $options['body'] . "</td></tr></table>";
// print_r($options);
		return $text;

	}

	/**
	 * Converts an array of values in form [0] => "name=value" into a real
	 * associative array in form [name] => value
	 *
	 * @param array string $options
	 * @return array $results
	 */
	static function extractOptions( $frame, array $args ) {
		$options = array();
		$rows = array();
	 
		foreach ( $args as $arg ) {
			//Convert args with "=" into an array of options
			$pair = explode( '=', $frame->expand($arg) , 2 );
			if ( count( $pair ) == 2 ) {
				$name = strtolower(trim( $pair[0] )); //Convert to lower case so it is case-insensitive
				$value = trim( $pair[1] );

				switch ($name) {
				    case 'title':
				        $title = $value;
				        break;
				    case 'duration':
				    	$duration = $value;
				        break;
				    case 'row':
					    //Split out the name from the value for the row
					    $row_pair = explode( "\r\n", $value , 2 );
					    if ( count( $row_pair ) == 2 ) {
					    	//Add to array $rows (e.g. EV1 => @ 30 Egress ...)
					    	$rows[$row_pair[0]] = $row_pair[1];
					    }
				        break;
			        default: //What to do with args not defined above
				}



				// $options[$name] = $value;
			}

			//The following is for reference and needs work
			// $pair = explode( '=', $frame->expand($arg) , 2 );
			// if ( count( $pair ) == 2 ) {
			// 	$name = strtolower(trim( $pair[0] )); //Convert to lower case so it is case-insensitive
			// 	$value = trim( $pair[1] );
			// 	$options[$name] = $value;
			// }
		}
		//Now you've got an array that looks like this:
		//	[title] => Block Title Example
		//	[body]  => Body of block
		//  [color] => Blue
		//  [width] => 2

		//Check for empties, set defaults
		//Default 'title'
		if ( !isset($options['title']) || $options['title']=="" ) {
		        	$options['title']= ""; //no default, but left here for future options
	        }

	    //Logic for $duration
	    //Need logic for
	    //1. What to do if not 14:254? (e.g. 'Dog')
	    //2. split hours:minutes and sum minutes
	    //3. default = 6:30
	    if isset($value){
	    	$input_time = explode( ':', $value , 2 );
		    if ( count ( $input_time ) == 2) {
		    	$hours = trim( $input_time[0] );
		    	$minutes = trim( $input_time[1] );
		    	$duration = ($hours * 60) + $minutes;
		    } else {
		    	$duration = $value;
		    }
		}

		return $options;
	}


	static function addCSS ( $out ){
		global $wgScriptPath;

// 		$out->addScriptFile( $wgScriptPath .'/extensions/SummaryTimeline/summary-timeline.js' );

		$out->addLink( array(
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => "screen",
			'href' => "$wgScriptPath/extensions/SummaryTimeline/SummaryTimeline.css"
		) );
		
		return true;
	}
}
