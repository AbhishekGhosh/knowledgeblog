
ROOT = ..

include $(ROOT)/publish/makefile_conf.inc


all: mathjax-latex kcite kblog-table-of-contents kblog-metadata kblog-author

mathjax-latex:
	$(CP) mathjax-latex $(PLUGINS)

kcite:
	$(CP) kcite $(PLUGINS)

kblog-metadata:
	$(CP) kblog-metadata $(PLUGINS)

kblog-table-of-contents:
	$(CP) kblog-table-of-contents $(PLUGINS)

kblog-author:
	$(CP) kblog-author $(PLUGINS)

post-metadata: 
	$(CP) post-metadata $(PLUGINS)

kb-coins-metadata:
	$(CP) kb-coins-metadata $(PLUGINS)

kb_doi:
	$(CP) kb_doi $(PLUGINS)

kblog-export:
	$(CP) kblog-export $(PLUGINS)

test-plugin:
	$(CP) test-plugin.php $(PLUGINS)

.PHONY: mathjax-latex kcite kblog-table-of-contents post-metadata kb-coins-metadata kblog-metadata kblog-author kblog-export

include $(ROOT)/publish/fix_perm.inc


