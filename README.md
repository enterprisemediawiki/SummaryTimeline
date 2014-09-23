SummaryTimeline
=========================

This extension creates a graphic representation of an EVA summary timeline.
The input is an array of tasks and durations for two crew members.

=========================
DEPENDENCIES
=========================

* Extension:Semantic Mediawiki
* Extension:Semantic Forms
* Extension:Semantic Internal Objects
* Extension:Variables
* Extension:NumerAlpha

other extensions?

Semantic form, templates, properties included in xml files

=========================
INSTALLATION
=========================

Download the extension files to your extensions directory. Add the following line to LocalSettings.php:

```html
require_once "$IP/extensions/SummaryTimeline/SummaryTimeline.php";
```

Import the following two files using the "Import files" feature in MediaWiki:
* SummaryTimeline-FormCategory&Templates.xml
* SummaryTimeline-Properties.xml

Additionally, you may import the following file to see some example pages:
* SummaryTimeline-ExtensionDemoPages.xml

For reference, these pages have been given the following categories to ease in the export process:

* Form Used for Summary Timeline Extension
* Category Used for Summary Timeline Extension
* Template Used for Summary Timeline Extension
* Property Used for Summary Timeline Extension
* Summary Timeline Extension Demo Page

=========================
ENTERING DATA
=========================

This is still in development.

Begin by clicking a link to Special:FormEdit/Summary_Timeline. There you can give a name to the timeline like "Failed FHRC Removal EVA Version 2". You can list out dependencies, related articles, the duration of the EVA, egress and ingress duration, and then the details of each task. If you choose to mark some tasks with a color, use the color key to explain what each color indicates. Clicking "Save page" creates the page with all the entered data.

The data is handled by the main Template:Summary Timeline and the multiple-instance templates for each actor. Currently, this extension is limited to the actors "IV", "EV1", and "EV2". In the future, I want to make this more flexible by adding the ability to create more actors and name them (EV1 and EV2 will remain required with the others as optional).

```html
{{#summary-timeline: title=US EVA 100
	| duration = 6:30 (default = 6:30)
	| row=EV1 
	@ 30 Egress 
	@ 40 SSRMS Setup <bgcolor:blue>
	@ 1:30 FHRC Release
	 ESP-2 FHRC

	@ 20 Maneuver from ESP-2 to S1

	@ 90 FHRC Install
	@ 45 SSRMS Cleanup
	@ 30 Get-Aheads (make this auto-fill based on EVA duration)
	@ 45 Ingress
	| row=EV2
	@ 30 Egress
	@ 40 FHRC Prep
	@ 90 FHRC Release
	@ 20 MMOD Inspection
	@ 110 FHRC Install
	@ 10 Get-Aheads
	@ 45 Ingress
 }}
  ```

=========================
DISPLAYING DATA
=========================

The output of a summary timeline is handled by Template:Summary Timeline Output.

For the compact version (default):
```html
{{Summary Timeline Output | Page name of summary timeline to display | Compact }}
  ```

To force the compact version to a fixed pixel width to fit on a specific page:
```html
{{Summary Timeline Output | Page name of summary timeline to display | Compact | 123 }}
  ```

For the full version:
```html
{{Summary Timeline Output | Page name of summary timeline to display | Full }}
  ```

This template generates a call to the #summary-timeline parser function and passes all the necessary information about the EVA and its tasks. The generic information about the EVA is queried from the page generated by the form as mentioned above. The task-specific information is queried from the Semantic Internal Objects created for each task.

```html
{{#summary-timeline: title=US EVA 100
	| duration = 6:30 (default = 6:30)
	| row=EV1 
	| 30 Egress 
	| 40 SSRMS Setup## blue
	| 1:30 FHRC Release
	 ESP-2 FHRC
	| 20 Maneuver from ESP-2 to S1
	| 90 FHRC Install
	| 45 SSRMS Cleanup
	| 30 Get-Aheads (make this auto-fill based on EVA duration)
	| 45 Ingress
	| row=EV2
	| 30 Egress
	| 40 FHRC Prep
	| 90 FHRC Release
	| 20 MMOD Inspection
	| 110 FHRC Install
	| 10 Get-Aheads
	| 45 Ingress
	| row = SSRMS
	| 30 Setup
	| 40 SSRMS Setup
	Brakes on
	| 1:30 FHRC Release
	GCA as required
	| 20 Maneuver from ESP-2 to S1
	| 90 FHRC Install
	GCA as required
	| 45 SSRMS Cleanup
	Brakes on
	| 30 Maneuver to park position
 }}
  ```