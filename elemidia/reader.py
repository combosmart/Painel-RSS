import tempfile
import scalatools as st
import scalalib as sl
import urllib2
import re
from xml.dom import minidom

svars = sl.sharedvars()
tempFolder = tempfile.gettempdir()

def downloadFile(url, type, file=None):
	downFile = st.grab_url(url, filename=file, minutes=0)
	if downFile.status == 0 or downFile.status == 304:
		return downFile
	else:
		checkFile(downFile.abspath, type)
		
def checkFile(file, type=None):
	if type == "dados":
		try:
			st.find_file(file)
			tmpCkFile = st.file_is_current(file, minutes=svars.validade)
			if not tmpCkFile:
				sl.log_external('Arquivo "'+file+'" fora da data de validade.')
				svars.ckFile = 'invalido'
				quit()
			else:
				svars.ckFile = 'valido'
		except:
			sl.log_external('Arquivo "'+file+'" invalido ou nao encontrado.')
			svars.ckFile = 'invalido'
			quit()
	elif type == "foto":
		try:
			st.find_file(file)
			svars.ckFoto = "valida"
		except:
			sl.log_external("Nao foi possivel encontrar a imagem: "+file)
			svars.ckFoto = "invalida"
		
def testeConexao(url):
	try:
		data = urllib2.urlopen(url,timeout=1000)
	except:
		return "desconectado"
	else:
		if data.getcode() == 200:
			return "conectado"
		else:
			return "desconectado"
			
def loopItens(arquivo, qtdItens):
	fileItemControl = tempFolder + "\\" + arquivo

	try:                                        
		open(fileItemControl)					
	except:
		tmpfile = open(fileItemControl, 'w')    
		tmpfile.write("1")
		tmpfile.close()
		numItem = 0			
	else:
		tmpfile = open(fileItemControl)         
		cont = tmpfile.read()
		try:
			int(cont)
		except:
			cont2 = 2
		else:
			cont2 = int(cont)
		tmpfile.close()
		
		if cont2 < qtdItens:         
			tmpfile = open(fileItemControl, 'w')
			tmpfile.write(str(cont2 + 1))       
			tmpfile.close()                     
			numItem = cont2                   
		else:                                   
			tmpfile = open(fileItemControl, 'w')
			tmpfile.write("1")                  
			tmpfile.close()                     
			numItem = 0   

	return numItem
	
conexao = testeConexao(svars.url)

if conexao == "conectado":
	### Baixa e valida arquivo de dados ###
	getFile = st.grab_url(url = svars.url, minutes=0)
	
	tmpFile = getFile.abspath
	
	checkFile(tmpFile, type='dados')
	### Baixa e valida arquivo de dados ###
	
	### Faz o parse do XML e pega o item atual ###
	xmldoc = minidom.parse(tmpFile)

	numItem = loopItens(svars.editoria+"_control.txt", len(xmldoc.getElementsByTagName('item')))
	
	item = xmldoc.getElementsByTagName('item')[numItem]
	### Faz o parse do XML e pega o item atual ###
	
	### Pega titulo ###
	svars.titulo = item.getElementsByTagName('title')[0].firstChild.nodeValue
	### Pega titulo ###
	
	### Pega texto ###
	svars.texto = item.getElementsByTagName('description')[0].firstChild.nodeValue
	### Pega texto ###
	
	### Pega e valida Foto ###
	getFoto = st.grab_url(url = item.getElementsByTagName('linkfoto')[0].firstChild.nodeValue, minutes=0)
	
	svars.ckFoto = checkFile(getFoto.abspath, type='foto')
	
	svars.foto = getFoto.abspath
	### Pega e valida Foto ###	
	
	### Pega credito foto ###
	svars.credFoto = item.getElementsByTagName('creditfoto')[0].firstChild.nodeValue
	### Pega credito foto ###
else:
	### Valida arquivo de dados ###
	tmpFile = tempFolder + "\\" + svars.editoria+".xml"
	
	checkFile(tmpFile, type='dados')
	### Valida arquivo de dados ###
	
	### Faz o parse do XML e pega o item atual ###
	xmldoc = minidom.parse(tmpFile)

	numItem = loopItens(svars.editoria+"_control.txt", len(xmldoc.getElementsByTagName('item')))
	
	item = xmldoc.getElementsByTagName('item')[numItem]
	### Faz o parse do XML e pega o item atual ###
	
	### Pega titulo ###
	svars.titulo = item.getElementsByTagName('title')[0].firstChild.nodeValue
	### Pega titulo ###
	
	### Pega texto ###
	svars.texto = item.getElementsByTagName('description')[0].firstChild.nodeValue
	### Pega texto ###
	
	### Pega e valida Foto ###
	tmpFoto = tempFolder + "\\" + (item.getElementsByTagName('linkfoto')[0].firstChild.nodeValue).split("/")[-1]

	svars.ckFoto = checkFile(tmpFoto, type='foto')
	
	svars.foto = tmpFoto
	### Pega e valida Foto ###	
	
	### Pega credito foto ###
	svars.credFoto = item.getElementsByTagName('creditfoto')[0].firstChild.nodeValue
	### Pega credito foto ###