# -*- Makefile -*-


### This make file contains sets various variables. All make files should
### include this file directly, and use the variables defined here rather than
### hard-coding equivalents. Default values should be set for the beta
### installation on wordpress. Local overrides for these defaults can be used
### on other development machines -- see includes section. 


###
### includes section
###

## This hieroglyics includes a configuration makefile in the same directory,
## if it exists, and another with the hostname in. No error is given if
## absent.
THIS_DIRECTORY = $(dir $(lastword $(MAKEFILE_LIST)))
-include $(THIS_DIRECTORY)makefile_conf_local.inc

HOST=$(shell hostname)
-include $(THIS_DIRECTORY)$(HOST)_local.inc


###
### Location variables
###

## the location of the local workpress installation
WORDPRESS?=/home/beta/wordpress

## location of the WP content directory
WPCONTENT?=$(WORDPRESS)/wp-content

## location to install themes
THEMES?=$(WPCONTENT)/themes

## location to install plugins
PLUGINS?=$(WPCONTENT)/plugins

###
### Commands
###

## 
## File manipulation
##

## Recursive copy command. 
CP?=rsync -vrt

## Recursive delete command
RRM?=HELLO rm -rf


##
## blogging command line tools
##

## blogpost -- asciidoc command line client for WP
BLOGPOST?=blogpost
## configuration for test, relative to root
BLOGPOST_TEST_CONF?=test/test-posts/build/test-vm.conf

## plastex -- latex to html converter
PLASTEX?=plastex


