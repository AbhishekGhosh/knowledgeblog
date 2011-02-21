# -*- Makefile -*-


fix_perm:
	find $(PLUGINS) -type d -exec chmod a+rx {} \;
	find $(PLUGINS) -type f -exec chmod a+r {} \;
	find $(THEMES) -type d -exec chmod a+rx {} \;
	find $(THEMES) -type f -exec chmod a+r {} \;


.PHONY: fix_perm