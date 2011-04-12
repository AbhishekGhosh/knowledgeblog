
ROOT = ..

include $(ROOT)/publish/makefile_conf.inc


all: mathjax-latex kcite kblog-table-of-contents post-metadata kb-coins-metadata kb_doi

mathjax-latex:
	$(CP) mathjax-latex $(PLUGINS)

kcite:
	$(CP) kcite $(PLUGINS)

kblog-table-of-contents:
	$(CP) kblog-table-of-contents $(PLUGINS)

post-metadata: 
	$(CP) post-metadata $(PLUGINS)

kb-coins-metadata:
	$(CP) kb-coins-metadata $(PLUGINS)

kb_doi:
	$(CP) kb_doi $(PLUGINS)

test-plugin:
	$(CP) test-plugin.php $(PLUGINS)

.PHONY: mathjax-latex kcite kblog-table-of-contents post-metadata kb-coins-metadata

include $(ROOT)/publish/fix_perm.inc


