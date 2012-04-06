<?php

/* 
  Copyright 2012 Phillip Lord, Newcastle University

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


  Proxy to fetch the bibtex for a DOI without requiring the use of content 
  negotiation. 

 */

$doi = $_GET[ "doi" ];
$url = "http://dx.doi.org/" . $doi;
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url );
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true );
curl_setopt ($ch, CURLOPT_MAXREDIRS, 6 );
curl_setopt ($ch, CURLOPT_HTTPHEADER,
             array (
                    "Accept: text/bibliography; style=bibtex" ) );

curl_exec ($ch);
curl_close($ch);

?>