<h1>Yago 3</h1>

<div class="code-container">
    <h2>Introduction and Statistics</h2>
YAGO3 combines the information from the Wikipedias in multiple languages with WordNet, GeoNames, and other data sources.  YAGO3 taps into multilingual resources of Wikipedia, getting to know more local entities and facts. In the current version, it has been extracted from 10 different Wikipedia versions (English, German, French, Dutch, Italian, Spanish, Polish, Romanian, Persian, and Arabic). YAGO3 is special in several ways:
<ol>
<li>YAGO3 combines the clean taxonomy of WordNet with the richness of the Wikipedia category system, assigning the entities to more than 350,000 classes.
<li>YAGO3 is anchored in time and space. YAGO attaches a temporal dimension and a spatial dimension to many of its facts and entities.
<li>In addition to taxonomy, YAGO has thematic domains such as "music" or "science" from WordNet Domains.
<li>YAGO3 extracts and combines entities and facts from 10 Wikipedias in different languages.
<li>YAGO3 contains canonical representations of entities appearing in different Wikipedia language editions.
<li>YAGO3 integrates all non-English entities into the rich type taxonomy of YAGO.
<li>YAGO3 provides a mapping between non-English infobox attributes and YAGO relations.
</ol>
YAGO3 knows more than 17 million entities (like persons, organizations, cities, etc.) and contains more than 150 million facts about these entities.  As with all major releases, the accuracy of YAGO3 has been manually evaluated, proving a confirmed accuracy of 95%. Every relation is annotated with its confidence value.
<p>
If you use YAGO2s for scientific purposes, please cite our paper:
<a href="https://www.linkedin.com/pub/farzaneh-mahdisoltani/45/822/20a">Farzaneh Mahdisoltani</a>, <a href="http://people.mpi-inf.mpg.de/~jbiega/">Joanna “Asia” Biega</a>, <a href="https://suchanek.name">Fabian M. Suchanek</a>:
			    		<br/>
		    			“YAGO3: A Knowledge Base from Multilingual Wikipedias”
			    		(<a href="https://suchanek.name/work/publications/cidr2015.pdf">pdf</a>)
			    		<br/>Full paper at the <a href="http://www.cidrdb.org/cidr2015/index.html">Conference on Innovative Data Systems Research</a>
		    				 (CIDR), 2015
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
<p></div>

<div class="code-container">
    <h2>Data (with license)</h2>
        The YAGO3 knowledge base is a set of independent modular full-text files, which together constitute the knowledge base.

    This data is available in two formats: TSV and Turtle.
    <p>
    The TSV format contains 5 columns: fact identifier, subject, predicate, object, numerical value of the object (if applicable).
    <p>
    The Turtle format is the native format of YAGO. It is in fact a backwards-compatible custom variant of Turtle, which stores the fact identifiers in a comment line before the actual fact. This means that they are not visible to systems that do not support this type of comments.
<p>

   The exact version number of this data is 3.0.2. The data was extracted from the following versions of Wikipedia: enwiki-20140626
dewiki-20130422
frwiki-20140315
nlwiki-UNKNOWN
itwiki-20140317
eswiki-20140320
rowiki-20140314
plwiki-20140312
arwiki-20140323
fawiki-20140319.

<a href=http://resources.mpi-inf.mpg.de/yago-naga/yago3.0.2/yago3_entire_ttl.7z>Turtle</a>, <a href=http://resources.mpi-inf.mpg.de/yago-naga/yago3.0.2/yago3_entire_tsv.7z>TSV</a>
    licensed under a Creative Commons Attribution 3.0 License by the YAGO team of the Max-Planck Institute for Informatics.
</div>

<div class="code-container">
    <h2>Code (with license)</h2>
If you are just interested in the data of YAGO, there is no need to use the present code repository. You can download data of YAGO above.
<p>
    The source code of YAGO is a Java project that extracts facts from Wikipedia and the other data sources, and stores these facts in files. These files make up the YAGO knowledge base.

If you run the code yourself, you can define (a) what Wikipedia languages to cover, and (b) which specific Wikipedia, Wikidata, and Wikimedia Commons snapshots should be used during the build.

    The YAGO3 source code is available at <a href=https://github.com/yago-naga/yago3>Github</a>. It is licensed under GNU General Public License, version 3 or later.
</div>
