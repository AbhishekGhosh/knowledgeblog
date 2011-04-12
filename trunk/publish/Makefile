
include makefile_conf_local.inc
include makefile_conf.inc



CP=rsync -vrt


all: submake permissions


permissions:
	find $(WORDPRESS) -type d -exec chmod a+rx {} \;
	find $(WORDPRESS) -type f -exec chmod a+r {} \;


submake:
	make -C ../external
	make -C ../plugins
