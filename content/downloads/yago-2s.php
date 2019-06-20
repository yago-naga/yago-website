<h1>Yago 2s</h1>

<div class="code-container">
    <h2>Introduction and Statistics</h2>
YAGO2s is an improved version of YAGO 2, with the following main characteristics:
<ul>
<li>YAGO2s is stored natively in Turtle, making it completely RDF/OWL compliant while still maintaining the fact identifiers that are unique to YAGO.
<li>The YAGO2s architecture enables cooperation of several contributors, facilitates debugging and maintenance. The data is divided into themes, so that users can download only particular pieces of YAGO ("YAGO à la carte").
<li>YAGO2s contains thematic domains such as "music" or "science", which gives a topic structure to YAGO.
</ul>
As all major releases, the accuracy of YAGO2s has been manually evaluated, proving an accuracy of 95% with respect to Wikipedia. 

If you use YAGO2s for scientific purposes, please cite our paper:
<a href="http://people.mpi-inf.mpg.de/~jbiega/">Joanna “Asia” Biega</a>, <a href="http://www.mpi-inf.mpg.de/~ekuzey/">Erdal Kuzey</a>, <a href="https://suchanek.name">Fabian M. Suchanek</a>:
			    		<br/>
		    			“Inside YAGO2s: A Transparent Information Extraction Architecture”
			    		(<a href="https://suchanek.name/work/publications/www2013demo.pdf">pdf</a>)
			    		<br/>Demo at the <a href="http://www.www2013.org/">World Wide Web Conference</a>
		    				 (WWW)
			    		
		    			, 2013
</div>

<div class="code-container">
    <h2>Dataformat / how to use</h2>
    
    YAGO classifies each entity into a taxonomy of classes. Every entity is an instance of one or multiple classes. Every class (except the root class) is a subclass of one or multiple classes. This yields a hierarchy of classes — the taxonomy. The YAGO taxonomy is the backbone of the ontology, and is designed with much care and attention to correctness.
For those interested in the details of that taxonomy, we provide here a more in-depth explanation of the classes. The taxonomy consists of 4 layers:
<ol>
<li>The root node of the taxonomy is rdfs:Resource. It includes entities, but also properties, literals, etc. rdfs:Resource has a subclass owl:Thing, which is the class of things (entities).
<li>Under owl:Thing, there is the class taxonomy from WordNet. Each class name is of the form <wordnet_XXX_YYY>, where XXX is the name of the concept (e.g., singer), and YYY is the WordNet 3.0 synset id of the concept (e.g., 110599806). For example, the class of singers is <wordnet_singer_110599806>. Each class is connected to its more general class by the rdfs:subclassOf relationship.
<li>The middle layer of the taxonomy consists of classes that have been derived from Wikipedia categories. For example, one class is <wikicategory_American_rock_singers>, derived from the Wikipedia category American rock singers. Each of these classes is connected to one class of the WordNet layer by a rdfs:subclassOf relationship. In the example, <wikicategory_American_rock_singers> rdfs:subclassOf <wordnet_singer_110599806>. Not all Wikipedia categories become classes in YAGO.
<li>The lowest layer of the taxonomy is the layer of instances. Instances comprise individual entities such as rivers, people, or movies. For example, this layer contains <Elvis_Presley>. Each instance is connected to one or multiple classes of the higher layers by the relationship rdf:type. In the example: <Elvis_Presley> rdf:type <wikicategory_American_rock_singers>.
</ol>
This way, you can walk from the instance up to its class by rdf:type, and then further up by rdfs:subclassOf.
<p>
    
    In YAGO (as in RDF), each fact consists of a subject, a predicate, and an object. Every fact can have a fact id. The fact id is simply computed as a hash from the subject, predicate, and object of the fact.   For example, the fact <Elvis_Presley> rdf:type <person> could have the fact identifier <id_42>. 
YAGO contains facts about these fact identifiers. For example, YAGO contains

<id_42> <occursSince> "1935-01-08"
<id_42> <occursUntil> "1977-08-16"
<id_42> <extractionSource> <http://en.wikipedia.org/Elvis_Presley>

These facts mean that Elvis was a person from the year 1935 to the year 1977, and that this fact was found in Wikipedia.
<p>
</div>

<div class="code-container">
    <h2>Data (with license)</h2>
    
    The YAGO2s knowledge base is a set of independent modular full-text files, which together constitute the knowledge base.

    This data is available in two formats: TSV and Turtle.
    <p>
    The TSV format contains 5 columns: fact identifier, subject, predicate, object, numerical value of the object (if applicable).
    <p>
    The Turtle format is the native format of YAGO. It stores the fact identifiers in a comment line before the actual fact. This means that they are not visible to systems that do not support this type of comments.


   The exact version number of this data is 2.5.3. The data was extracted from the 2012-12-01 version of Wikipedia.
<a href=http://resources.mpi-inf.mpg.de/yago-naga/yago2.5/yago2s_ttl.7z>Turtle</a>, <a href=http://resources.mpi-inf.mpg.de/yago-naga/yago2.5/yago2s_tsv.7z>TSV</a>
    licensed under a Creative Commons Attribution 3.0 License by the YAGO team of the Max-Planck Institute for Informatics.
    
</div>

<!--<i>No code, so no box</i>-->