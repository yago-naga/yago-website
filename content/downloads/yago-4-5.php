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
    <p>YAGO is thus a simplified, cleaned, and “reasonable” version of Wikidata. It contains 49 million entities and 109 million facts. See above for <a href="/getting-started">getting started</a>!</p>
    <p>If you use YAGO 4.5 for scientific purposes, please cite our paper:</p>
    <blockquote>
	<a href="https://suchanek.name">Fabian M. Suchanek</a>, <a href="https://sites.google.com/view/mehwish-alam/home">Mehwish Alam</a>, <a href="https://perso.telecom-paristech.fr/bonald/Home_page.html">Thomas Bonald</a>, <a href="https://chenlihu.com/">Lihu Chen</a>, <a href="https://phparis.net/">Pierre-Henri Paris</a>, <a href="">Jules Soria</a>:
			    		<br/>                        			    		
		    			<b><a href="https://suchanek.name/work/publications/sigir-2024.pdf">YAGO 4.5: A Large and Clean Knowledge Base with a Rich Taxonomy</a></b>
        <br/>
		Resource paper at the <a href="https://sigir-2024.github.io/">Conference on Research and Development in Information Retrieval</a> (SIGIR), 2024
    </blockquote>
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
    <li><b>Schema:</b>  The upper taxonomy, constraints, and property definitions in SHACL (<a href=/data/yago4.5/samples/schema.txt>sample</a>). The schema is explained <a href="/schema">here</a>.
    <li><b>Taxonomy:</b>  The full taxonomy of classes. (<a href=/data/yago4.5/samples/taxonomy.txt>sample</a>)
    <li><b>Facts:</b>  All facts about entities that have an English Wikipedia page. (<a href=/data/yago4.5/samples/facts.txt>sample</a>)
	<li><b>Facts beyond Wikipedia:</b>  All facts about entities that do not have an English Wikipedia page. (<a href=/data/yago4.5/samples/beyond-wikipedia.txt>sample</a>)
    <li><b>Meta:</b>  The fact annotations (“facts about facts”) in RDF*. (<a href=/data/yago4.5/samples/meta.txt>sample</a>)
    </ul>
    <div class=fmsbuttaround>
	<a class=fmsbutt href="/data/yago4.5/">Download</a>
	</div>
    <p>
    YAGO can then be loaded into any triple store, such as
            <a href="https://jena.apache.org/documentation/rdfstar/">Jena</a>,
            <a href="https://rdf4j.org/documentation/programming/rdfstar/">RDF4J</a>,
            <a href="https://github.com/rdfjs/N3.js/">N3.js</a>
            <a href="http://rdf.greggkellogg.net/yard/file.rdf-README.html#rdf-rdfstar">RDF.rb</a>,
            <a href="https://wiki.blazegraph.com/wiki/index.php/Reification_Done_Right">Blazegraph</a>,
            <a href="https://docs.cambridgesemantics.com/anzograph/v2.2/userdoc/lpgs.htm">AnzoGraph</a>,
            <a href="https://www.stardog.com/blog/property-graphs-meet-stardog/">Stardog</a>,
            <a href="http://graphdb.ontotext.com/documentation/9.2/free/devhub/rdf-sparql-star.html">GraphDB</a>, or <a href="https://github.com/ad-freiburg/qlever/">Qlever</a>.
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