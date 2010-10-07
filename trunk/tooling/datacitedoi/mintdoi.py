import httplib2, sys, base64
from optparse import OptionParser
from xml.etree import cElementTree as ElementTree

def main(user, password, metadata, testmode):
	#httplib2 allows us to deal with authentication
	h = httplib2.Http()
	auth_string = base64.encodestring(user + ':' + password)
	t = "0"
	#set the test mode - if the -t flag is found, we're testing (so not minting DOIs)
	if testmode:
		t = "1"
	#this is the actual API call
	#we can't mint multiple DOIs for the same URL - will update existing
	response, content = h.request('https://datacite.org.uk/api/dataset?testMode=' + t,
									  'POST',
									  body = open(metadata).read(),
									  headers={'Accept':'application/xml',
											   'Content-Type':'application/xml',
											   'Authorization':'Basic ' + auth_string})

	if (response.status != 201):
		raise Exception('DOI not created, response code: ' + str(response.status))

	#print(content)
	#prettier printing by processing the output XML
	element = ElementTree.XML(content)
	for child in element.getchildren():
		print(child.tag, child.text)

if __name__ == '__main__':
	#parse command line
	parser = OptionParser(usage="Usage: %prog [options] metadata.xml",
			version="%prog 0.1")
	parser.add_option("-u", "--user", dest="user", help="Datacite Username", metavar="USER")
	parser.add_option("-p", "--password", dest="passwd", help="Datacite Password", metavar="PASSWD")
	parser.add_option("-t", "--test", dest="test", help="testMode", action="store_true", default=False)
	(options, args) = parser.parse_args()
	if len(args) != 1:
		print('Usage error, you need to define a metadata XML file.')
		parser.print_help()
	elif not options.user or not options.passwd:
		print('Usage error, you need to define a DataCite username AND password.')
		parser.print_help()	
	else:
		main(options.user, options.passwd, args[0], options.test)
