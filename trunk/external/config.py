"""Configure options for fetch_external_plugins"""
config = {
    #define the root directory of your Wordpress/Knowledgeblog installation
    'wordpress_root':'.',
    #plugins from wordpress.org, not written by kblog team
    'external_dependencies': ['co-authors-plus',
                'post-revision-display',
                'wp-post-to-pdf',
                'edit-flow',
                'epub-export',
                'syntaxhighlighter',
                'wp-gravatar',
                'wordpress-table-of-contents',
                ],
    #plugins from wordpress.org, written by kblog team
    'knowledgeblog_dependencies': ['kcite',
                'mathjax-latex',
                'knowledgeblog-table-of-contents',
                ],
    #base url for plugin downloading
    'root_url': 'http://downloads.wordpress.org/plugin/',
    #which plugins do you not want kept at the latest version?
    #and what version do you wish to maintain them at?
    #key:value e.g.: 'kcite':'1.0'
    'override_versions':{},
}
