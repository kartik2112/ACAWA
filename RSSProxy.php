<?php
$docRoot = new DOMDocument('1.0');
$docRoot -> load('http://feeds.feedburner.com/carandbike-latest');
echo $docRoot -> saveXML();