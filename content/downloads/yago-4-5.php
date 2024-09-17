<div class="code-container">
    <h1>YAGO 4.5</h1>
    <p>
       YAGO 4.5 is the latest version of the YAGO knowledge base. It is based on <a href="https://wikidata.org">Wikidata</a> &mdash; the largest public general-purpose knowledge base. YAGO refines the data as follows:
    </p>
    <ol>
        <li>All entity identifiers and property identifiers are human-readable.
        <li>The top-level classes come from <a href="http://schema.org">schema.org</a> &mdash; a standard repertoire of classes and properties maintained by Google and others. The lower level classes are a careful selection of the Wikidata taxonomy.
        <li>The properties come from schema.org.
        <li>YAGO 4.5 contains semantic constraints in the form of SHACL. These constraints keep the data clean, and allow for  logical reasoning on YAGO.
    </ol>
    <p>YAGO is thus a simplified, cleaned, and “reasonable” version of Wikidata. It contains 49 million entities and 109 million facts.</p>
    <p>If you use YAGO 4.5 for scientific purposes, please cite our paper:</p>
    <blockquote>
	<a href="https://suchanek.name">Fabian M. Suchanek</a>, <a href="https://sites.google.com/view/mehwish-alam/home">Mehwish Alam</a>, <a href="https://perso.telecom-paristech.fr/bonald/Home_page.html">Thomas Bonald</a>, <a href="https://chenlihu.com/">Lihu Chen</a>, <a href="https://phparis.net/">Pierre-Henri Paris</a>, <a href="">Jules Soria</a>:
			    		<br/>                        			    		
		    			<b><a href="https://suchanek.name/work/publications/sigir-2024.pdf">YAGO 4.5: A Large and Clean Knowledge Base with a Rich Taxonomy</a></b>
        <br/>
		Resource paper at the <a href="https://sigir-2024.github.io/">Conference on Research and Development in Information Retrieval</a> (SIGIR), 2024
    </blockquote>
	<a class=butt href="/data/yago4.5/">Download</a>
</div>

<div class="code-container">
    <h1>How to use YAGO</h1>
    <p>
        YAGO is an RDFS knowledge base. It is a collection of facts, each of which consists of a subject, a predicate, and an object &mdash; as in <code>yago:Elvis_Presley rdf:type schema:Person</code>.
    </p>
    <p>
    YAGO puts each entity into at least one class. The classes form a taxonomy, where the higher classes are taken from <a href="http://schema.org">schema.org</a>, and the lower classes from <a href="https://www.wikidata.org">Wikidata</a>.  The highest class is <code>schema:Thing</code>.
    </p>
    <p>
    The facts come from <a href="https://wikidata.org">Wikidata</a>, and the predicates have been mapped manually to the predicates of <a href=http://schema.org>schema.org</a>. Facts whose predicates could not be mapped were omitted. All predicates, all classes, and most entities have human-readable names. YAGO entities are mapped with <code>owl:sameAs</code> to Wikidata and with <code>schema:sameAs</code> to WordNet and other sources.
    </p>
    <p>
    YAGO comes with SHACL constraints that specify the disjointness of certain classes, as well as the domains, ranges, and cardinalities of relations. Please find a detailed description of the upper taxonomy as well as our design document <a href="https://yago-knowledge.org/schema">here</a>.
    </p>
</div>

<div class="code-container">
    <h1>Data</h1>
	
    <p>
        YAGO 4.5 licensed under a <a href="https://creativecommons.org/licenses/by-sa/3.0/">Creative Commons Attribution-ShareAlike</a> by the YAGO team of Télécom Paris.
        Some facts are imported from schema.org that releases its data <a href="http://schema.org/docs/terms.html">under the same license</a>.
    </p>
    <p>
        The YAGO 4.5 knowledge base consists of the following set of Turtle files:
    </p>
    <ul   class="browser-default">
    <li><b>Schema:</b>  The upper taxonomy, constraints, and property definitions in SHACL.
    <li><b>Taxonomy:</b>  The full taxonomy of classes.
    <li><b>Facts:</b>  All facts about entities that have an English Wikipedia page.
	<li><b>Facts beyond Wikipedia:</b>  All facts about entities that do not have an English Wikipedia page.
    <li><b>Meta:</b>  The fact annotations (“facts about facts”) in RDF*.
    </ul>

<a class=butt href="/data/yago4.5/">Download</a>

    <p>
        The <code>.ntx</code> files are using RDF* (a.k.a. RDF star) N-Triples syntax.
        It can be parsed using
            <a href="https://jena.apache.org/documentation/rdfstar/">Jena</a>,
            <a href="https://rdf4j.org/documentation/programming/rdfstar/">RDF4J</a>,
            <a href="https://github.com/rdfjs/N3.js/">N3.js</a>
            <a href="http://rdf.greggkellogg.net/yard/file.rdf-README.html#rdf-rdfstar">RDF.rb</a>,
            <a href="https://wiki.blazegraph.com/wiki/index.php/Reification_Done_Right">Blazegraph</a>,
            <a href="https://docs.cambridgesemantics.com/anzograph/v2.2/userdoc/lpgs.htm">AnzoGraph</a>,
            <a href="https://www.stardog.com/blog/property-graphs-meet-stardog/">Stardog</a>, or
            <a href="http://graphdb.ontotext.com/documentation/9.2/free/devhub/rdf-sparql-star.html">GraphDB</a>.
    </p>
</div>

<div class="code-container">
    <h1>Code</h1>
    <p>
        If you are just interested in the data of YAGO, there is no need to use the present code repository. You can
        download data of YAGO above.
    </p>
    <p>
        The source code of YAGO is a Python project that ingests facts from Wikidata, and transforms them into YAGO. If you run the code yourself, you can add other sources or modify the generation of the knowledge base. The YAGO 4.5 source code is
        available at <a rel="noreferrer noopener" target="_blank" href=https://github.com/yago-naga/yago-4.5>Github</a>. It
        is licensed under the <a href=http://creativecommons.org/licenses/by/4.0/>Creative Commons Attribution License</a>.
    </p>
</div>

<div class="code-container">
<h1>Acknowledgements</h1>

<p>YAGO can only be so large because it is based on other sources. We would like to thank</p>
<ul class="browser-default">
    <li>
        the creators and contributors of <a href="https://www.wikidata.org" rel="noreferrer noopener" target="_blank">Wikidata</a>.
        <br/>
        Thank you for having and implementing such an ambitious vision of building a “Wikipedia for machines”, and thank
        you for keeping it open!
    </li>
    <li>
        the team of <a href="http://schema.org" rel="noreferrer noopener" target="_blank">Schema.org</a>, who created the taxonomy and the properties that we use in YAGO 4.5.
    </li>   
</ul>
</div>