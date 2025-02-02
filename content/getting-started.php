<h1>Getting Started</h1>

<ul class="browser-default">
    <li><a href="#what-is-yago">What is YAGO?</a></li>
    <li><a href="#yago-special">What is so special about YAGO?</a></li>
	<li><a href="#yago-cmp">How does YAGO compare to other knowledge bases?</a></li>
	<li><a href="#constraints">What are the logical constraints of YAGO?</a></li>
    <li><a href="#data-model">What is the data model of YAGO?</a></li>
    <li><a href="#relations">What are the relations in YAGO?</a></li>
    <li><a href="#taxonomy">What is the taxonomy of YAGO?</a></li>
    <li><a href="#access">How can I access YAGO?</a></li>
</ul>

<h2 id="what-is-yago">What is YAGO?</h2>
<p>
    YAGO is a knowledge base, i.e., a database with knowledge about the real world. YAGO contains both entities (such as movies, people, cities, countries, etc.) and relations between these entities (who played in which movie, which city is located in which country, etc.). All in all, YAGO contains more than 50 million entities and 90 million facts.
</p>
<p>
YAGO arranges its entities into classes: Elvis Presley belongs to the class of people, Paris belongs to the class of cities, and so on. These classes are arranged in a taxonomy: The class of cities is a subclass of the class of populated places, this class is a subclass of geographical locations, etc.
</p>
<p>
YAGO also defines which relations can hold between which entities: birthPlace, e.g., is a relation that can hold between a person and a place. The definition of these relations, together with the taxonomy is called the ontology.
</p>

<h2 id="yago-special">What is so special about YAGO?</h2>
<p>YAGO combines two great resources:</p>
<ol>
    <li>
        <a href=https://www.wikidata.org>Wikidata</a> is the largest general-purpose knowledge base on the Semantic Web. It is a great repository of entities, but it has a difficult taxonomy and no human-readable entity identifiers.
    </li>
    <li>
        <a href=http://schema.org>schema.org</a> is a standard ontology of classes and relations, which is maintained by Google and others &mdash; but it does not have any entities.
    </li>
</ol>
<p>
YAGO combines these two resources, thus getting the best from both worlds: a huge repository of facts, together with an ontology that is simple and used as a standard by a large community. In addition, all identifiers in YAGO are human-readable, all entities belong to at least one class, and only classes and properties with enough instances are kept. To this, YAGO adds a system of logical constraints. These do not just keep the data clean, but also allow for reasoning on the data. YAGO is thus a simplified, cleaned, and “reasonable” version of Wikidata.
</p>

<h2 id="yago-cmp">How does YAGO compare to other knowledge bases?</h2>
<div>
YAGO positions itself as a large general knowledge base for facts about instances, with a taxonomy, manually defined properties, and logical constraints. Its key property is that it is a centrally controlled data source, which allows it to establish certain guarantees for the quality of its data.
<ul> 
<li>- YAGO differs from <a href=https://dbpedia.org/>DBpedia</a>, because YAGO has a predefined schema, predefined and non-redundant relations, and logical constraints. The manually curated part of DBpedia has all of these, too, but contains only 4 million instances. YAGO contains 50 million.
<li>- YAGO differs from <a href=https://conceptnet.io/>ConceptNet</a> by being about instances, and not about common sense knowledge.
<li>- YAGO differs from <a href=https://babelnet.org/>BabelNet</a> by having a taxonomy and a schema.
<li>- YAGO differs from <a href=https://en.wikipedia.org/wiki/Freebase_(database)>Freebase</a> by being an actively maintained project.
<li>- YAGO differs from <a href=https://wikidata.org>Wikidata</a> by having human-readable identifiers, a clean top-level taxonomy, and enforced logical constraints.
</ul>
For a more detailed discussion, see our scientific paper:
<blockquote>
	<a href="https://suchanek.name">Fabian M. Suchanek</a>, <a href="https://sites.google.com/view/mehwish-alam/home">Mehwish Alam</a>, <a href="https://perso.telecom-paristech.fr/bonald/Home_page.html">Thomas Bonald</a>, <a href="https://chenlihu.com/">Lihu Chen</a>, <a href="https://phparis.net/">Pierre-Henri Paris</a>, <a href="">Jules Soria</a>:
			    		<br/>                        			    		
		    			<b><a href="https://suchanek.name/work/publications/sigir-2024.pdf">YAGO 4.5: A Large and Clean Knowledge Base with a Rich Taxonomy</a></b>
        <br/>
		Resource paper at the <a href="https://sigir-2024.github.io/">Conference on Research and Development in Information Retrieval</a> (SIGIR), 2024
    </blockquote>
</div>

<h2 id="constraints">What are the logical constraints of YAGO?</h2>
<p>
Logical constraints are conditions that the data must fulfill. For example, a logical constraint can say that no entity can be at the same time a person and a place. These constraints serve to root out errors in the data, and establish the logical coherence of the knowledge base. The constraints also allow for making deductions: If someone asks whether Elvis is a place, then we can answer “no”, because we know he is a person. While this may sound trivial, such reasoning is not possible without the logical constraint.
YAGO currently has the following logical constraints:
</p>
<ul  class="browser-default">
<li>Disjointness: Place, person, and creative works are disjoint classes
<li>Functionality: several relations (such as birthPlace) can have at most one object
<li>Domain and range: for every relation, we define which class the subject and the object belong to
</ul>

<h2 id="data-model">What is the data model of YAGO?</h2>
<p>YAGO is stored in the standard Resource Description Framework “RDF”. This means that YAGO is a set of facts, each of which consists of a subject, a predicate (also called “relation” or “property”) and an object &mdash; as in <code>&lt;Elvis&gt; &lt;birthPlace&gt; &lt;Tupelo&gt;</code>. </p>
<p>
We use different vocabularies for the components of such a fact. For example, for the predicates, we use the relations that are defined by schema.org. Therefore, RDF requires that we prefix the predicates with <code>schema:</code>. This method allows us to refer to standard vocabulary without re-inventing the wheel.
</p>
<p>
For “facts about facts” (such as time stamps for facts or other types of annotations), we use the RDF* format.
</p>

<h2 id="relations">What are the relations in YAGO?</h2>
<p>The relations in YAGO come from schema.org. We have mapped the original relations of Wikidata manually to these relations. We discard all Wikidata relations that do not have a schema.org-equivalent. This cuts away a large number of predicates that had very few facts.
</p>

<h2 id="taxonomy">What is the taxonomy of YAGO?</h2>
<p>
The top-level taxonomy of YAGO is taken from schema.org. In this way, we have a simple hierarchy of classes that has proven to work well in practice. However, these classes are not fine-grained enough. For example, they do not know “electric cars”. Therefore, we have carefully integrated selected parts of the Wikidata taxonomy into YAGO. 
</p>

<h2 id="access">How can I access YAGO?</h2>
<p>There are several ways to access YAGO:</p>
<ol>
    <li>You can browse yourself through the knowledge base in our <a href=https://yago-knowledge.org/graph/Elvis_Presley>Web Interface</a></li>
    <li>You can launch SPARQL queries in our <a href=sparql>SPARQL endpoint</a></li>
	<li>You can programmatically send queries to our <a href=sparql>SPARQL endpoint</a></li>
    <li>You can <a href=downloads>download</a> data and load it into an RDF triple store. (e.g., BlazeGraph or Jena). <br><i>This is the preferred method if you plan to launch a larger number of queries!</i>
    </li>
</ol>