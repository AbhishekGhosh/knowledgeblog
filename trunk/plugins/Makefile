
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

test-plugin:
	$(CP) test-plugin.php $(PLUGINS)

.PHONY: mathjax-latex kcite kblog-table-of-contents post-metadata kb-coins-metadata kblog-metadata kblog-author

include $(ROOT)/publish/fix_perm.inc


