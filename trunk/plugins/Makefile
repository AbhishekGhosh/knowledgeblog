
ROOT = ..

include $(ROOT)/publish/makefile_conf.inc


all: mathjax-latex kcite kblog-table-of-contents kblog-metadata kblog-author

mathjax-latex:
	$(CP) mathjax-latex $(PLUGINS)

kcite:
	$(CP) kcite $(PLUGINS)

kblog-metadata:
	$(CP) kblog-metadata $(PLUGINS)

kblog-export:
	$(CP) kblog-export $(PLUGINS)

test-plugin:
	$(CP) test-plugin.php $(PLUGINS)

.PHONY: mathjax-latex kcite kblog-metadata kblog-export test-plugin

include $(ROOT)/publish/fix_perm.inc


