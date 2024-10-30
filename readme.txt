=== Knights of Columbus - State ===
Contributors: falcon13
Donate link: https://onthegridwebdesign.com/software/kofc-state-plugin/
Tags: knights of columbus, widget, shortcode, kofc
Requires at least: 4.0
Tested up to: 6.6
Requires PHP: 5.6
Stable tag: 2.5.0
License: GPLv3

Display the status of trails on your website.

== License ==
Released under the terms of the GNU General Public License.

== Description ==
This plugin is for Knights of Columbus State Councils. It add three post types, Knights, Councils and Assemblies, for use in a directory. Each will have their own page. Also the list of councils is used for a recruiting and star council scoreboard. The scoreboard also has a separate list of Knights for showing an individual knight's recruiting. This plugin was initially designed for the Utah state council.

= Features =
*   Council, Assembly and Knight post types that create pages with email forms you can use in a directory
*   Shortcode for displaying a list of councils
*   Scoreboards shortcode for recruiting by knight, council and district
*   Scoreboard shortcode for showing which star council requirements a council has completed

DISCLAIMER: Under no circumstances do we release this plugin with any warranty, implied or otherwise. We cannot be held responsible for any damage that might arise from the use of this plugin. Back up your WordPress database and files before installation.

== Installation ==
After activating go to the settings page. Here you can set the number of districts you have. To enable spam reduction on the email forms, Google reCaptcha keys are required. Links to the settings page are under Settings in the admin and on the plugins page.
The admin menu will have the two new post types in the main admin menu along with the Scoreboard and Inbound Messages menu items.

= Knight and Council Pages =
Once you create a knight or council post, you can link it to its page:
http://[site.domain]/knight/[post-title]
http://[site.domain]/council/[council #]
http://[site.domain]/assembly/[assembly #]

== Shortcode [otgkofcs_scoreboard] ==
= Options =
type: "councils", "knights" or "districts" (default: none)

= Examples =
[otgkofcs_scoreboard type="knights"]
[otgkofcs_scoreboard type="councils"]

== Shortcode [otgkofcs_star_reqs] ==
No options

= Examples =
[otgkofcs_star_reqs]

== Shortcode [otgkofcs_council_table] and [otgkofcs_assembly_table] ==
These create a table with no additional styling (it uses the themes styling for a table). For styling create a css class and add its name in the options.
= Options =
class: css class for the table

= Examples =
[otgkofcs_council_directory class="pretty_table"]

== Shortcode [otgkofcs_council_box_list] & [otgkofcs_assembly_box_list] ==
No options

= Examples =
[otgkofcs_council_box_list]

== Screenshots ==
1. Council Page
2. Admin Add Council Page
3. Star Council Page
4. Recruiting Scoreboard
5. Admin Star Requirements Page
6. Admin Council List Page
7. Admin Settings Page
8. Admin Add Knight Page

== Changelog ==
2.5.0 (9/10/2024)
- Updated validation and filter helper functions, including splitting validation and filter functions into separate helper files.

2.4.4 (6/1/2024)
- Small tweaks

2.4.3 (5/5/2023)
- Tweaked forms

2.4.2 (12/8/2022)
- Updated validation and view helpers. Code improvements.
- Datatables JS library update.

2.4.1 (2/27/2022)
- Fixed council type edit page not showing the saved charter date.

2.4 (2/6/2022)
- Replaced reCaptcha with hCaptcha
- Added assembly to council.
- Changed lists to use datatables (except post types). Also merged setting knights scores into the knights list (non-post type).

2.3 (5/14/2020)
- Added Assembly post type
- Enhanced council table and box list shortcodes and added copies of them for assemblies
- The otgkofcs_council_directory shortcode was replaced with otgkofcs_council_table and will be removed in a future version

2.2.2 (4/20/2019)
- Added jQuery UI Stylesheet

2.2.1 (4/18/2019)
- First WordPress Directory release.
- Minor wording and style changes.

2.2.0 (3-12-2019)
- Updated all form data to go through filters.
- Made frontend type pages theme independent.
- Added more sorting to post types and list pages in admin.
- The original shortcode [otgkofcs] renamed [otgkofcs_scoreboard]
- Checked for and remove/updated outdated and unsecure code.
- Checked for and fixed function and other names were not using standards for this plugin.
- Small bug fixes.

2.1.0 (11-11-2018)
- Added charter date to councils.
- Added sender name & email into message body and sender name into from.

2.0.0 (6/6/2018)
- Change from just a recruiting and star council scoreboard to handling directory listings.
- Knight and Council post types added.
- Old council table and related admin removed; scoreboard uses post type list.
- Added storage for message sent through post type pages.
- Council directory list shortcode.

== Frequently Asked Questions ==

= Are the knights on the scoreboard and the knights post type the same? =
* No. They are separate so that the post type is only for the directory and has more information. The knights under the scoreboard are only for the scoreboards.
= Will there be a council finder for prospective and new members? =
* It's planned.
= Can you make creating state office holders and program directors lists that use the knight post type? =
* It's also planned.
= What about version 1.0? =
* The first version was only the scoreboard and had different name. It was not released and only used on the Utah Knights website.

