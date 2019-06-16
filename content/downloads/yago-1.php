<h1>Yago 1</h1>

<div class="code-container">
    <h2>Introduction and Statistics</h2>
This is the 2008 version of YAGO. It knows more than 2 million entities (like persons, organizations, cities, etc.). It knows 20 million facts about these entities. This version of YAGO  includes the data extracted from the categories and infoboxes of Wikipedia, combined with the taxonomy of WordNet. YAGO1 was manually evaluated, and found to have an accuracy of 95% with respect to the extraction source.
<p>
If you use YAGO1 for scientific purposes, please cite our paper:
   <a href="https://suchanek.name">Fabian M. Suchanek</a>, <a href="http://www.mpii.mpg.de/~kasneci">Gjergji Kasneci</a>, <a href="http://www.mpii.mpg.de/~weikum">Gerhard Weikum</a>:
			    		<br/>
		    			“Yago - A Core of Semantic Knowledge”
			    		(<a href="https://suchanek.name/work/publications/www2007.pdf">pdf</a>)
			    		<br/>Full paper at the <a href="http://www2007.org/">World Wide Web Conference</a>
		    				 (WWW)
</div>

<div class="code-container">
    <h2>Dataformat / how to use</h2>
    YAGO classifies each entity into a taxonomy of classes. Every entity is an instance of one or multiple classes. Every class (except the root class) is a subclass of one or multiple classes. This yields a hierarchy of classes — the taxonomy. The YAGO taxonomy is the backbone of the ontology, and is designed with much care and attention to correctness.
    
</div>

<div class="code-container">
    <h2>Data (with license)</h2>
    
    YAGO1 comes in 3 different formats: native, RDF/XML, and N3. While RDF/XML and N3 are the standard formats, the native format is as follows:

* The folder "facts" contains the main data
  There is one subfolder for each relation
  Each subfolder contains several files.
  Each of these files contains lines of the form
    <factId> TAB <arg1> TAB <arg2> TAB <confidence>
  One such line means that <arg1> and <arg2> stand in the relation
  given by the subfolder, with an accumulated confidence <confidence>.

* The folder "Entities" contains all entities
  There are several files, each of which contains lines of the form
     <entity> TAB <isConcept> TAB <URL>
  where <isConcept> is either true or false and tells whether the
  entity is a concept. <URL> is an URL that describes the entity
  (or null). This table is compiled as additional information from the
  relationships TYPE and DESCRIBES.

Note that, if a folder contains multiple files, all of these files are
part of the knowledge base.
<p>
    The YAGO1 knowledge base is licensed under the GNU Free Documentation License,
see http://www.gnu.org/copyleft/fdl.html .
The exact version number of this data is 1.0.0. The data was extracted from the 2008-10-01 version of Wikipedia. It
<a href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago1/YAGO1.0.0/yago.zip>native</a> (1GB), <a href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago1/YAGO1.0.0/rdfs.zip>RDF/XML</a> (1GB), <a href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago1/YAGO1.0.0/n3.zip>N3</a> (250Mb)

</div>

<i>Code is not available, so no code box</i>