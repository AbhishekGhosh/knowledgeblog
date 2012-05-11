##
## Copyright 2010 Phillip Lord, Newcastle University
##
## This file is part of latextowordpress
##

## This program is free software: you can redistribute it and/or modify it
## under the terms of the GNU Lesser General Public License as published by
## the Free Software Foundation, either version 3 of the License, or (at your
## option) any later version.

## This program is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
## GNU Lesser General Public License for more details.

## You should have received a copy of the GNU Lesser General Public License
## along with this program.  If not, see <http://www.gnu.org/licenses/>.

from plasTeX.Renderers.XHTML import XHTML as _Renderer
import re

class Wordpress(_Renderer):

    latex_math_replacements = {
        "$>$" : "&gt;",
        "$<$" : "&lt;",
        "$\sim $" : "&#126;",
    }

    ## kludge -- PageTemplate overrides any attributes set where they are
    ## defined in the templates. These allow me to switch this off. 
    prerender = True
    keys_set_prerender = []
    
    def __init__(self, *args, **kwargs):
        self.file = kwargs.pop( 'file' );

        _Renderer.__init__(self, *args,**kwargs)
        for name in ['math','displaymath','eqnarray']:
            self[name] = self.do_math
        
        self['includegraphics'] = self.do_includegraphics
        self['cite'] = self.do_cite
                
        self.prerender = False


    def __setitem__(self,key,value):
        
        if( self.prerender ):
            self.keys_set_prerender.append(key)
            return _Renderer.__setitem__(self,key, value)
        else:
            if not key in self.keys_set_prerender:
                return _Renderer.__setitem__(self,key,value)
                
                
    ## don't do any of this stuff. 
    def doJavaHelpFiles(self, document, encoding='ISO-8859-1', version='2'):
        pass

    def doEclipseHelpFiles(self, document, encoding='ISO-8859-1'):
        pass
        
    def doCHMFiles(self, document, encoding='ISO-8859-1'):
        pass
        
    def do_includegraphics(self, node):
        return u'<img src="%s">' % node.attributes['file']

    def do_math(self, node):
        if( node.source in self.latex_math_replacements ):
            return u'%s' % self.latex_math_replacements[ node.source ]

        return u'[latex]%s[/latex]' % node.source[1:-1]

    def do_cite(self, node):
        if not hasattr( self, "citation" ):
            self.parse_bbl_file()
            print self.citation
            
        key = node.source[6:-1]
        ref = "Unknown"

        print "checking for %s" % key
        if( key in self.citation ):
            ref = self.citation[ key ]
            print "found"

        return u'[cite]%s[/cite]' % ref


    def parse_bbl_file(self):
        self.citation = {}
        ## we are in the output directory -- .bbl file is in directory above. 
        file = "../" + self.file.split(".")[ 0 ] + ".bbl"

        current_item = False
        current_doi = False
        current_url = False
        
        ## state
        moving_over_prolog = True
        bib_item_searching_for_key = False
        
        with open(file) as f:
            for line in f:
                if(line.startswith("\\bibitem")):
                    bib_item_searching_for_key = True
                    if( not moving_over_prolog ):
                        if( current_doi or current_url ):
                            self.citation[ current_item ] = current_doi or current_url
                        current_item = False
                        current_doi = False
                        current_url = False
                    moving_over_prolog = False


                if(bib_item_searching_for_key):
                    m = re.search("\{(.*)\}", line)
                    if( m ):
                        current_item = m.group( 1 )
                        bib_item_searching_for_key = False


                if(line.startswith( "\\newblock \\doi")):
                    current_doi = "http://dx.doi.org/" + line[15:-3]
                    
                if(line.startswith( "\\newblock URL")):
                    current_url = line[19:-3]
                    

                    
Renderer = Wordpress
