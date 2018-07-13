import xml.etree.ElementTree as ET
import urllib2

filename='http://brin.elemidia.com.br/seahorse/misc/monitoramento/monitoramento_status_all.php'
tree = ET.parse(urllib2.urlopen(filename))
root = tree.getroot()
myArray=[]

for x in root.findall('predio'):
    myArray.append(x.text)

print(myArray)  