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
        _Renderer.__init__(self, *args,**kwargs)
        for name in ['math','displaymath','eqnarray']:
            self[name] = self.do_math

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
        

    def do_math(self, node):
        if( node.source in self.latex_math_replacements ):
            return u'%s' % self.latex_math_replacements[ node.source ]

        return u'[latex]%s[/latex]' % node.source[1:-1]

Renderer = Wordpress
