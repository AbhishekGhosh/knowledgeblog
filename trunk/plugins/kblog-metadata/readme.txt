=== Kblog Metadata ===

Contributors: philliplord
Tags: res-comms, scholar, academic, science
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 0.5

Tools for exposing and editing the bibliographic metadata of academic posts. 

== Description ==

This plugin enables other software to extract who, what and when information
from a blog and its posts. It is part of the Knowledgeblog project
(http://knowledgeblog.org), which is developing plugins to improve WordPress
as a tool for academic publishing, either for individual authors, or for
conferences and workshops publishing proceedings to the web. As well as this
file, additional documentation is available at
[process](http://process.knowledgeblog.org/category/kblog-metadata).

It is often useful to embed bibliographic metadata, describing the author(s),
title and publication date into a web page. There are a variety of different
ways of doing this, described in a variety of different specifications and/or
standards. These vary widely in their formality, uptake and age, as well as
clarity with which the specification is written. 

The practical upshot of this is that automatic capture of metadata which
enables tools such as Greycite (http://greycite.knowledgeblog.org) and various
bibliographic software to work is a somewhat ad hoc affair. Sometimes it
works, sometimes it does not. Rather than requiring users to add a separate
plugin for each of these specifications, kblog-metadata takes the approach of
adding metadata in as many formats as possible, in the hope that, for any
tool, at least one will work. 

Kblog Metadata enhances the ability of WordPress to expose and edit
bibliographic metadata of academic posts. It consists of a number of 
pieces of functionality

* kblog-headers -- adds invisible metadata
* kblog-authors -- allows multiple authors, without requring WordPress accounts
* kblog-table-of-contents -- displays all posts in a variety of formats. 
* kblog-title -- set container titles ("blogname") per post or using a custom taxonomy.
* kblog-boilerplate -- displays citation information as widget or in post
  content
* kblog-download -- downloaded bib or other formats for posts

We will include new formats or specifications where possible, so long as they
are not too computationally intensive. Please send email to the [mailing
list](mailto:knowledgeblog@googlegroups.com) if you are interested in a new format.

== Kblog Headers ==

There are many tools to which academics may want to advertise their work. We
currently support three independent standards which are:

1. COinS (http://ocoins.info).
1. Meta tags as suggested by Google Scholar.
1. Open Graph Protocol (http://ogp.me) 

These will be automatically added to add pages and posts on installation of
the plugin. The metadata is taken either from the user profile, the WordPress
metadata, or from Kblog Author metadata. 

== Kblog Table of Contents == 

The table of contents functionality comes in two forms: one designed for
embedding in an existing page, and one for computational consumption. To add a
table of contents to a page add a "shortcode" to your post contents. 

&#91;kblogtoc&#93;

Additionally, it is also possible to retrieve a simple HTML or plain text
representation of the table of contents from (http://blogurl/?kblog-toc=txt)
or (http://blogurl/?kblog-toc=html). Author information comes from 
Kblog Author.

You can specify the default category for the table of contents from the
Settings Menu, or accept the default which is to show them all. 

== Kblog Authors ==

Academic writing is more often multi-author than not, yet this is poorly
supported within WordPress. While there are existing co-author plugins these
often require assigning multiple user accounts, one per author, even though
many authors will never login to WordPress. Within Kblog Authors you can add
"display authors", totally independently from WordPress accounts. They will
appear on Kblog Table of Contents and in metadata generated by Kblog Headers. 

Authors can be added either on the "Edit Post" page of Wordpress, or through
the use of an &#91;author&#93; shortcode within the document content. Authors
specified within the post content take precedence.  

== Kblog Title == 

Authors may wish to alter the apparent title of their blog for a post or a
group of posts. For example, I may wish to publish a paper that I have written
for a conference on my own blog, but wish the metadata to refer to the
conference. Alternatively, as with
[bio-ontologies](http://bio-ontologies.knowledgeblog.org) I may wish to host
multiple meetings on a single website (one per year, for instance), and have
the year, or meeting number, appear in the metadata. Kblog Title allows both
of these uses, by allowing the user to set the container name ("blogname")
either for an individual post, or using an Event tag. 


== Acknowledgements ==

kblog-metadata includes the HumanNameParser from Jason Priem
(http://jasonpriem.org/human-name-parse/) which is licensed under the MIT
License. 

== Installation ==

1. Unzip the downloaded .zip archive to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Upgrade Notice ==

= 0.5 = 

1. Bug fix release. 

= 0.4 = 
 
1. New Headers and Simple HTML capabilities added

= 0.3 = 

1. New Widget Sets added displaying Citation and Download options

= 0.2 = 

1. Can now support multiple event titles on a single blog.

== Changelog ==

= 0.5 = 

1. Changes to the way entities are encoded in titles, hopefully preventing
   "double escaping"
1. Update to handling of name parsing. Single names should no longer cause a
   crash (but will be treated as surnames). 

= 0.4 = 

1. New headers added to enable Mendeley
1. SimpleHTML option added to Download

= 0.3 = 

1. Widgets have been added displaying Citation and Download data. 
1. Reworked Options page.
1. Escaping issues in Titles (hopefully) fixed. 
1. All features should now work on pages as well as posts. 


= 0.2 = 

1. kblog-title now allows setting of container titles independent of
wordpress. 

= 0.1 =

Initial release

== Upgrade Notice ==

Initial Release

== Copyright ==

This plugin is copyright Phillip Lord, Newcastle University and is licensed
under GPLv2. 
