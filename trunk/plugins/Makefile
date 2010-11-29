
ROOT = ..

include $(ROOT)/publish/makefile_conf.inc


all: mathjax-latex
	$(CP) simon_first.php $(PLUGINS)
	$(CP) reference $(PLUGINS)

mathjax-latex:
	$(CP) mathjax-latex $(PLUGINS)

.PHONY: mathjax-latex

include $(ROOT)/publish/fix_perm.inc


