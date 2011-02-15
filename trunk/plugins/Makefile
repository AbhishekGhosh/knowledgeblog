
ROOT = ..

include $(ROOT)/publish/makefile_conf.inc


all: mathjax-latex kcite
	$(CP) reference $(PLUGINS)

mathjax-latex:
	$(CP) mathjax-latex $(PLUGINS)

kcite:
	$(CP) kcite $(PLUGINS)

.PHONY: mathjax-latex kcite

include $(ROOT)/publish/fix_perm.inc


