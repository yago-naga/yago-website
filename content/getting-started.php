<h1>Getting Started</h1>

<ul class="browser-default">
    <li><a href="#what-is-yago">What is YAGO?</a></li>
    <li><a href="#yago-special">What is so special about YAGO?</a></li>
	<li><a href="#yago-cmp">How does YAGO compare to other knowledge bases?</a></li>
	<li><a href="#constraints">What are the logical constraints of YAGO?</a></li>
    <li><a href="#data-model">What is the data model of YAGO?</a></li>
    <li><a href="#access">How can I access YAGO?</a></li>
</ul>

<h2 id="what-is-yago">What is YAGO?</h2>
<p>
    YAGO is a knowledge base, i.e., a database with knowledge about the real world. YAGO contains both entities (such as movies, people, cities, countries, etc.) and relations between these entities (who played in which movie, which city is located in which country, etc.). All in all, YAGO contains more than 39 million entities and 167 million facts.
</p>
<p>
YAGO arranges its entities into classes: Elvis Presley belongs to the class of people, Paris belongs to the class of cities, and so on. These classes are arranged in a taxonomy: The class of cities is a subclass of the class of populated places, this class is a subclass of geographical locations, etc.
</p>
<p>
YAGO also defines which relations can hold between which entities: birthPlace, e.g., is a relation that can hold between a person and a place. The definition of these relations is called the schema.
</p>

<h2 id="yago-special">What is so special about YAGO?</h2>
<p>YAGO comes with a manually defined schema, which imposes logical constraints on the data. For example, people can be married only to people, and they can have at most one birth date. These constraints keep the data logically consistent and ensure its quality. YAGO can thus be considered a logically consistent subset of the much larger (but not consistent) Wikidata knowledge base. </p> 

<h2 id="yago-cmp">How does YAGO compare to other knowledge bases?</h2>
<div>
YAGO positions itself as a large general knowledge base for facts about instances, with a taxonomy, manually defined properties, and logical constraints. Its key property is that it is a centrally controlled data source, which allows it to establish certain guarantees for the quality of its data.
<ul> 
<li>- YAGO differs from <a href=https://dbpedia.org/>DBpedia</a>, because YAGO has a predefined schema, predefined and non-redundant relations, and logical constraints. The manually curated part of DBpedia has all of these, too, but contains only 5 million instances. YAGO contains 39 million.
<li>- YAGO differs from <a href=https://schema.org/>Schema.org</a> by having data about instances, and by being available under a Creative Commons Attribution license, which allows commercial usage (starting from YAGO 4.6).
<li>- YAGO differs from <a href=https://conceptnet.io/>ConceptNet</a> by being about instances, and not about common sense knowledge.
<li>- YAGO differs from <a href=https://babelnet.org/>BabelNet</a> by being available (starting from YAGO 4.6) under a liberal Creative Commons Attribution license, which allows commercial usage.
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
<li>Disjointness: Place, person, and medical entities are disjoint classes
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

<h2 id="access">How can I access YAGO?</h2>
<p>There are several ways to access YAGO:</p>
<ol>
    <li>You can browse yourself through the knowledge base in our <a href=https://yago-knowledge.org/resource/Elvis_Presley>Web Interface</a></li>
    <li>You can launch SPARQL queries in our <a href=sparql>SPARQL endpoint</a></li>
	<li>You can programmatically send queries to our <a href=sparql>SPARQL endpoint</a></li>
    <li>You can <a href=downloads>download</a> data and load it into an RDF triple store. (e.g., BlazeGraph or Jena). <br><i>This is the preferred method if you plan to launch a larger number of queries!</i>
    </li>
</ol>