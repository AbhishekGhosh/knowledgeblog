#!/usr/bin/python

##
## This code is modified from the plasTeX (http://plastex.sourceforge.net/)
## launch script. 
##


## The original plasTeX executable is Copyright 2007 Kevin D. Smith, and was
## been released under the following license:
## License:
##    Permission is hereby granted, free of charge, to any person obtaining a copy
##    of this software and associated documentation files (the "Software"), to deal
##    in the Software without restriction, including without limitation the rights
##    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
##    copies of the Software, and to permit persons to whom the Software is
##    furnished to do so, subject to the following conditions:
##
##    The above copyright notice and this permission notice shall be included in
##    all copies or substantial portions of the Software.
##
##    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
##    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
##    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
##    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
##    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
##    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
##    THE SOFTWARE.
##
## Modifications are Copyright 2010 Phillip Lord, Newcastle University. 
##
## Modifications are distributed under the terms of the GNU LGPL license. 

import os, sys, codecs, string, glob
import plasTeX

from plasTeX.TeX import TeX
from plasTeX.Config import config
from plasTeX.ConfigManager import *
from plasTeX.Logging import getLogger

log = getLogger()

## PWL hard code import!
from knowledgeblog.wordpress import Renderer

__version__ = '0.1'

def main(argv):

    print >>sys.stderr, 'latextowordpress version %s' % __version__

    # Parse the command line options
    try: 
        opts, args = config.getopt(argv[1:])
    except Exception, msg:
        log.error(msg)
        print >>sys.stderr, config.usage()
        sys.exit(1)
        
    if not args:
        print >>sys.stderr, config.usage()
        sys.exit(1)

    ##PWL inject non standard options so that they are appopriate
    config['general']['theme'] = "minimal"
    config['files']['split-level'] = "0"
    config['files']['filename'] = "$jobname.html"
    file = args.pop(0)

    # Create document instance that output will be put into
    document = plasTeX.TeXDocument(config=config)

    # Instantiate the TeX processor and parse the document
    tex = TeX(document, file=file)

    # Populate variables for use later
    if config['document']['title']:
        document.userdata['title'] = config['document']['title']
    jobname = document.userdata['jobname'] = tex.jobname
    cwd = document.userdata['working-dir'] = os.getcwd()

    # Load aux files for cross-document references
    pauxname = '%s.paux' % jobname
    rname = config['general']['renderer']
    for dirname in [cwd] + config['general']['paux-dirs']:
        for fname in glob.glob(os.path.join(dirname, '*.paux')):
            if os.path.basename(fname) == pauxname:
                continue
            document.context.restore(fname, rname)

    # Parse the document
    tex.parse()


    # Set up TEXINPUTS to include the current directory for the renderer
    os.environ['TEXINPUTS'] = '%s:%s' % (os.getcwd(), 
                                         os.environ.get('TEXINPUTS',''))

    # Change to specified directory to output to
    outdir = config['files']['directory']
    if outdir:
        outdir = string.Template(outdir).substitute({'jobname':jobname})
        if not os.path.isdir(outdir):
            os.makedirs(outdir)
        log.info('Directing output files to directory: %s.' % outdir)        
        os.chdir(outdir)
    
    renderer = Renderer( file=file )
    renderer.render(document)

try:
    main(sys.argv)
except KeyboardInterrupt:
    pass
