<h1>Yago 2</h1>

<div class="code-container">
    <h2>Introduction and Statistics</h2>
    YAGO 2 is an improved version of the original YAGO. Its main characteristics are:
<ul>
<li>YAGO2 is a knowledge base that is anchored in time and space. YAGO2 attaches a temporal dimension and a spacial dimension to many of its facts and entities.
<li>YAGO2 is particularly suited for disambiguation purposes, as it contains a large number of names for entities. It also knows the gender of people.
<li>
As all major releases, the accuracy of YAGO2 has been manually evaluated, proving an accuracy of 95% with respect to Wikipedia. Every relation is annotated with its confidence value.
</ul>
If you use YAGO2 for scientific purposes, please cite our paper:

<a href="http://www.mpi-inf.mpg.de/~jhoffart">Johannes Hoffart</a>, <a href="https://suchanek.name">Fabian M. Suchanek</a>, <a href="http://mpi-inf.mpg.de/~kberberi/">Klaus Berberich</a>, <a href="http://www.mpii.mpg.de/~weikum">Gerhard Weikum</a>:
			    		<br/>
		    			“YAGO2: A Spatially and Temporally Enhanced Knowledge Base from Wikipedia”
			    		(<a href="https://suchanek.name/work/publications/aaaij.pdf">pdf</a>)
			    		<br/>Journal article in the <a href="http://www.cl.uni-heidelberg.de/~ponzetto/aij/">Artificial Intelligence</a>
		    			, 2013<br/><a href="http://aij.ijcai.org/index.php/aij-awards" style="color:red">Prominent Paper Award</a>
		    			
</div>

<div class="code-container">
    <h2>Dataformat / how to use</h2>
    YAGO classifies each entity into a taxonomy of classes. Every entity is an instance of one or multiple classes. Every class (except the root class) is a subclass of one or multiple classes. This yields a hierarchy of classes — the taxonomy. The YAGO taxonomy is the backbone of the ontology, and is designed with much care and attention to correctness.
    <p>
  In YAGO (as in RDF), each fact consists of a subject, a predicate, and an object. Every fact can have a fact id.  For example, the fact <Elvis_Presley> rdf:type <person> could have the fact identifier <id_42>. 
YAGO contains facts about these fact identifiers. For example, YAGO contains

<id_42> <occursSince> "1935-01-08"
<id_42> <occursUntil> "1977-08-16"
<id_42> <extractionSource> <http://en.wikipedia.org/Elvis_Presley>

These facts mean that Elvis was a person from the year 1935 to the year 1977, and that this fact was found in Wikipedia.
<p>

</div>

<div class="code-container">
    <h2>Data (with license)</h2>
    
    YAGO2 is available in two data formats: the native format and RDF. While RDF is the standard for triple data, the native format has the advantage that it stores the fact identifier. The native format consists of a set of files, which together constitute the knowledge base. The files that start with an underscore are internal files that will be of little use to the end-user. The other files are named after the relation they contain. For example, the file "ismarriedto.tsv" is a TSV file that contains 3 columns: a fact id, a subject, and an object &mdash; meaning that the subject was married to the object, and that this fact has the given fact id.

<p>
    The version number of this data is 2.3.0. The data was extracted from the 2010-08-17 version of Wikipedia.
    <a href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago2/YAGO2.3.0__yago2full_20120109/YAGO2.3.0__yago2full_20120109.7z>native</a>, <a href=http://resources.mpi-inf.mpg.de/yago-naga/yago1_yago2/download/yago2/YAGO2.3.0__yago2full_20120109/YAGO2.3.0__yago2full_20120109.rdfs.7z>RDF</a>
        licensed under a Creative Commons Attribution 3.0 License by the YAGO team of the Max-Planck Institute for Informatics.
</div>

<!--<i>No code, so no box</i>-->