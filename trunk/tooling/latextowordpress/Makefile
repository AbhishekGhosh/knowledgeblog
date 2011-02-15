

ROOT = ../..

include $(ROOT)/publish/makefile_conf.inc

PLASTEX_DEBUG=$(HOME)/src/ht/knowledgeblog/plastex/plastex/
LTWP=export PYTHONPATH=$(PLASTEX_DEBUG);./latextowordpress.py
##LTWP=./latextowordpress.py

CATEGORIES=latextowordpress-test


## if we want to get posts to update rather repost, we need the post-ids.
## blogpost stores these, but then the clean target needs to be more complex,
## and ultimately will be less good. So, there is no choice but to store the
## ids here. So, we add an override file here. This file would look something
## like this.

# PSID=--post-id=
# ST=14
# MM=44
# SE=32

## If you want to find what the name of your include file is, change
## "-include" to "include". make will crash with an error message telling you.
## To get the post id's, post first with no variables set. You should see the
## post ids on the command line, but you can always look them up in wordpress. 

## All a bit clunky. The makefile should work anyway, but you will get a new
## set of posts each time you run clean.
HOST = $(shell hostname)
-include $(HOST)_postids.inc



all: unit sample

##
## Unit tests
## 
unit: simple_test_publish math_mode_publish lists_publish


simple_test: 
	$(LTWP) -d 'test-output/$$jobname' test-files/simple_test/simple_test.tex

simple_test_publish: simple_test
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) --doctype=html \
		--title="Latex Simple Test" $(PSID)$(ST) \
	 	post test-output/simple_test/simple_test.html 
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) $(PSID)$(ST) \
                --categories=$(CATEGORIES) cat

math_mode:
	$(LTWP) -d 'test-output/$$jobname' test-files/math_mode/math_mode.tex

math_mode_publish: math_mode
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) --doctype=html \
		--title="Math Mode" $(PSID)$(MM) \
	 	post test-output/math_mode/math_mode.html
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) $(PSID)$(MM) \
               --categories=$(CATEGORIES) cat

lists:
	$(LTWP) -d 'test-output/$$jobname' test-files/lists/lists.tex

lists_publish: lists
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) --doctype=html \
		--title="Lists" $(PSID)$(LL) \
	 	post test-output/lists/lists.html
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) $(PSID)$(LL) \
                --categories=$(CATEGORIES) cat


theorem:
	$(LTWP) -d 'test-output/$$jobname' test-files/theorem_test/theorem_test.tex


##
## Standard sample documents
##
sample: sample2e_publish 

sample2e: 
	$(LTWP) -d 'test-output/$$jobname' test-files/sample2e/sample2e.tex

sample2e_publish: sample2e
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) --doctype=html \
		--title="Sample 2e" $(PSID)$(SE) \
	 	post test-output/sample2e/sample2e.html
	$(BLOGPOST) -f $(ROOT)/$(BLOGPOST_TEST_CONF) $(PSID)$(SE) \
                --categories=$(CATEGORIES),latex-samples cat




clean:
	$(RM) -r test-output/





##
## packaging
##
DIR=../latextowordpress/
VERSION=0.1
FILES=$(DIR)latextowordpress.py $(DIR)knowledgeblog $(DIR)licence.txt $(DIR)README.txt

package:
	tar -cvz --exclude='*.pyc' $(FILES) -f latextowordpress-$(VERSION).tgz