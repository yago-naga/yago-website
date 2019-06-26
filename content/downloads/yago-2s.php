<div class="code-container">
    <h1>YAGO2s</h1>

    <p>YAGO2s is an improved version of YAGO 2, with the following main characteristics:</p>

    <ul class="browser-default">
        <li>
            YAGO2s is stored natively in Turtle, making it completely RDF/OWL compliant while still maintaining the fact
            identifiers that are unique to YAGO.
        </li>

        <li>
            The YAGO2s architecture enables cooperation of several contributors, facilitates debugging and maintenance.
            The data is divided into themes, so that users can download only particular pieces of YAGO ("YAGO à la
            carte").
        </li>

        <li>YAGO2s contains thematic domains such as "music" or "science", which gives a topic structure to YAGO.</li>
    </ul>

    <p>
        As all major releases, the accuracy of YAGO2s has been manually evaluated, proving an accuracy of 95% with
        respect
        to Wikipedia.
    </p>

    <p>
        If you use YAGO2s for scientific purposes, please cite our paper:
        <br/>
        <a rel="noreferrer noopener" target="_blank" href="http://people.mpi-inf.mpg.de/~jbiega/">Joanna “Asia”
            Biega</a>,
        <a rel="noreferrer noopener" target="_blank" href="http://www.mpi-inf.mpg.de/~ekuzey/">Erdal Kuzey</a>,
        <a rel="noreferrer noopener" target="_blank" href="https://suchanek.name">Fabian M. Suchanek</a>:
        <br/>
        <i>Inside YAGO2s: A Transparent Information Extraction Architecture</i>
        (<a rel="noreferrer noopener" target="_blank"
            href="https://suchanek.name/work/publications/www2013demo.pdf">pdf</a>)
        <br/>
        Demo at the <a rel="noreferrer noopener" target="_blank" href="http://www.www2013.org/">
            World Wide Web Conference (WWW)</a>, 2013
    </p>

</div>

<div class="code-container">
    <h1>How to use YAGO2s</h1>

    <p>
        YAGO classifies each entity into a taxonomy of classes. Every entity is an instance of one or multiple classes.
        Every class (except the root class) is a subclass of one or multiple classes. This yields a hierarchy of classes
        —
        the taxonomy. The YAGO taxonomy is the backbone of the ontology, and is designed with much care and attention to
        correctness.
        <br/>
        For those interested in the details of that taxonomy, we provide here a more in-depth explanation of the
        classes.
        The taxonomy consists of 4 layers:
    </p>

    <ol>
        <li>
            The root node of the taxonomy is <code>rdfs:Resource</code>. It includes entities, but also properties,
            literals, etc.
            <code>rdfs:Resource</code> has a subclass <code>owl:Thing</code>, which is the class of things (entities).
        </li>

        <li>
            Under <code>owl:Thing</code>, there is the class taxonomy from WordNet. Each class name is of the form
            <code>&lt;wordnet_XXX_YYY&gt;</code>,
            where XXX is the name of the concept (e.g., singer), and YYY is the WordNet 3.0 synset id of the concept
            (e.g., 110599806). For example, the class of singers is <code>&lt;wordnet_singer_110599806&gt;</code>. Each
            class is
            connected to its more general class by the <code>rdfs:subclassOf</code> relationship.
        </li>

        <li>
            The middle layer of the taxonomy consists of classes that have been derived from Wikipedia categories. For
            example, one class is <code>&lt;wikicategory_American_rock_singers&gt;</code>, derived from the Wikipedia
            category American rock singers. Each of these classes is connected to one class of the WordNet layer by a
            <code>rdfs:subclassOf</code> relationship. In the example,
            <code>&lt;wikicategory_American_rock_singers&gt;</code> <code>rdfs:subclassOf</code>
            <code>&lt;wordnet_singer_110599806&gt;</code>. Not all Wikipedia categories become classes in YAGO.
        </li>

        <li>
            The lowest layer of the taxonomy is the layer of instances. Instances comprise individual entities such as
            rivers, people, or movies. For example, this layer contains <code>&lt;Elvis_Presley&gt;</code>. Each
            instance is connected to one or multiple classes of the higher layers by the relationship rdf:type. In the
            example: <code>&lt;Elvis_Presley&gt;</code> <code>rdf:type</code> <code>&lt;wikicategory_American_rock_singers&gt;</code>.
        </li>
    </ol>

    <p>
        This way, you can walk from the instance up to its class by rdf:type, and then further up by <code>rdfs:subclassOf</code>.
    </p>

    <p>
        In YAGO (as in RDF), each fact consists of a subject, a predicate, and an object. Every fact can have a fact id.
        The fact id is simply computed as a hash from the subject, predicate, and object of the fact. For example, the
        fact &lt;Elvis_Presley&gt; rdf:type &lt;person&gt; could have the fact identifier &lt;id_42&gt;.
        YAGO contains facts about these fact identifiers. For example, YAGO contains
    </p>

    <ul>
        <li><code>&lt;id_42&gt; &lt;occursSince&gt; "1935-01-08"</code></li>
        <li><code>&lt;id_42&gt; &lt;occursUntil&gt; "1977-08-16"</code></li>
        <li><code>&lt;id_42&gt; &lt;extractionSource&gt; &lt;http://en.wikipedia.org/Elvis_Presley&gt;</code></li>
    </ul>

    <p>
        These facts mean that Elvis was a person from the year 1935 to the year 1977, and that this fact was found in
        Wikipedia.
    </p>
</div>

<div class="code-container">
    <h1>Data (with license)</h1>
    <p>
        The YAGO2s knowledge base is a set of independent modular full-text files, which together constitute the
        knowledge base.
    </p>

    <p>This data is available in two formats: TSV and Turtle.</p>

    <ul class="browser-default">
        <li>
            The TSV format contains 5 columns: fact identifier, subject, predicate, object, numerical value of the
            object (if applicable).
        </li>
        <li>
            The Turtle format is the native format of YAGO. It stores the fact identifiers in a comment line before the
            actual fact. This means that they are not visible to systems that do not support this type of comments.
        </li>
    </ul>

    <p>
        The exact version number of this data is 2.5.3. The data was extracted from the 2012-12-01 version of Wikipedia.
        <a rel="noreferrer noopener" target="_blank"
           href=http://resources.mpi-inf.mpg.de/yago-naga/yago2.5/yago2s_ttl.7z>Turtle</a>,
        <a rel="noreferrer noopener" target="_blank"
           href=http://resources.mpi-inf.mpg.de/yago-naga/yago2.5/yago2s_tsv.7z>TSV</a>
        licensed under a Creative Commons Attribution 3.0 License by the YAGO team of the Max-Planck Institute for
        Informatics.
    </p>
</div>
