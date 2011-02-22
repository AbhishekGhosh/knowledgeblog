
import sys

print( sys.path )

from knowledgeblog.simple import Renderer

from plasTeX.TeX import TeX
# Instantiate a TeX processor and parse the input text
tex = TeX()
tex.ownerDocument.config['files']['split-level'] = -100
tex.ownerDocument.config['files']['filename'] = 'test.xml'
tex.input(r'''
\documentclass{book}
\begin{document}

Previous paragraph.

\section{My Section}

\begin{center}
Centered text with <, >, and \& charaters.
\end{center}

Next paragraph.

Here is some stuff in $x$ math mode. 

\end{document}
''')
document = tex.parse()




# Render the document
renderer = Renderer()
renderer.render(document)



