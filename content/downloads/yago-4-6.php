<div class="code-container">
    <h1>YAGO 4.6</h1>
    <p>
       YAGO 4.6 is the latest version of the YAGO knowledge base. It is based on <a href="https://wikidata.org">Wikidata</a> &mdash; the largest public general-purpose knowledge base. YAGO refines the data as follows:
    </p>
    <ol>
        <li>All entity identifiers and property identifiers are human-readable.
        <li>The top-level classes and properties are manually defined, and use the identifiers of <a href="http://schema.org">schema.org</a> &mdash; a standard repertoire of classes and properties maintained by Google and others. The lower level classes are a careful selection of the Wikidata taxonomy.
        <li>YAGO contains semantic constraints in the form of SHACL. These constraints keep the data clean, and allow for  logical reasoning on YAGO.
		<li>YAGO 4.6 includes also files with the statements that were excluded from Wikidata.
    </ol>
    <p>YAGO is thus a simplified, cleaned, and “reasonable” version of Wikidata. It contains 39 million entities and 167 million facts. See above for <a href="/getting-started">getting started</a>!</p>
    <p>If you use YAGO for scientific purposes, please cite our paper:</p>
    <blockquote>
	<a href="https://suchanek.name">Fabian M. Suchanek</a>, <a href="https://perso.telecom-paristech.fr/bonald/Home_page.html">Thomas Bonald</a>, <a href="https://fr.linkedin.com/in/nikola-dobricic">Nikola Dobricic</a>, <a href="https://marc.jeanmougin.fr/">Marc Jeanmougin</a>, <a href="https://www.linkedin.com/in/margherita-necchi-04777a214/">Margherita Necchi</a>:<br>

	<a href="https://suchanek.name/work/publications/iswc-2026-demo-yago.pdf">YAGO 4.6: A Logically Consistent Subset of Wikidata</a>
			    		<br/>Demo paper at the <a href="https://iswc2026.semanticweb.org/">International Semantic Web Conference</a>
		    				(ISWC), 2026

    </blockquote>
</div>

<div class="code-container">
    <h1>Data</h1>
	
    <p>
        YAGO 4.6 licensed under a <a href="https://creativecommons.org/licenses/by/4.0/">Creative Commons Attribution</a> by the YAGO team of Télécom Paris.
    </p>
    <p>
        The YAGO 4.6 knowledge base consists of the following set of Turtle files:
    </p>
    <ul   class="browser-default">
    <li><b>Schema:</b>  The upper taxonomy, constraints, and property definitions in SHACL (<a href=/data/yago4.6/samples/schema.txt>sample</a>). The schema is explained <a href="/schema">here</a>.
    <li><b>Taxonomy:</b>  The full taxonomy of classes. (<a href=/data/yago4.6/samples/taxonomy.txt>sample</a>)
    <li><b>Facts:</b>  All facts about entities that have an English Wikipedia page. (<a href=/data/yago4.6/samples/facts.txt>sample</a>)
    <li><b>Labels:</b>  All labels about entities that have an English Wikipedia page. (<a href=/data/yago4.6/samples/labels.txt>sample</a>)
	<li><b>Facts beyond Wikipedia:</b>  All facts about entities that do not have an English Wikipedia page. (<a href=/data/yago4.6/samples/beyond-wikipedia.txt>sample</a>)
    <li><b>Labels beyond Wikipedia:</b>  All labels about entities that do not have an English Wikipedia page. (<a href=/data/yago4.6/samples/labels-beyond-wikipedia.txt>sample</a>)
	<li><b>Meta:</b>  The fact annotations (“facts about facts”) in RDF*. (<a href=/data/yago4.6/samples/meta.txt>sample</a>)
    <li><b>Logs:</b>  The logs of statements that were excluded, in RDF*, in 3 files. (<a href=/data/yago4.6/samples/logs.txt>sample</a>)	
    </ul>
    <div class=fmsbuttaround>
	<a class=fmsbutt href="/data/yago4.6/">Download</a>
	</div>
    <p>
    YAGO can then be loaded into any triple store. On our end, we use <a href="https://github.com/ad-freiburg/qlever/">Qlever</a>. 
    </p>	
</div>

<div class="code-container">
    <h1>Code</h1>
    <p>
        If you are just interested in the data of YAGO, there is no need to use the present code repository. You can
        download data of YAGO above.
    </p>
    <p>
        The source code of YAGO is a Python project that ingests facts from Wikidata, and transforms them into YAGO. If you run the code yourself, you can add other sources or modify the generation of the knowledge base. The YAGO source code is
        available at <a rel="noreferrer noopener" target="_blank" href=https://github.com/yago-naga/yago-4.6>Github</a>. It
        is licensed under the <a href=http://creativecommons.org/licenses/by/4.0/>Creative Commons Attribution License</a>.
    </p>
</div>

<div class="code-container">
<h1>Acknowledgements</h1>

<p>YAGO can only be so large because it is based on other sources. We would like to thank the creators and contributors of <a href="https://www.wikidata.org" rel="noreferrer noopener" target="_blank">Wikidata</a> Thank you for having and implementing such an ambitious vision of building a “Wikipedia for machines”, and thank
        you for keeping it open!
</div>
