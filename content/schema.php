<h1>Schema</h1>

This is the top-level taxonomy of classes of YAGO. Please find detailed descriptions in our <a href="https://yago-knowledge.org/data/yago4.5/design-document.pdf">Design Document</a>.
 <ul>
        <li><details><summary class='yagoClass'>schema:Thing</summary><details><summary>Outgoing properties</summary><ul>
<li>- rdfs:comment &rarr;<sup>1 per language</sup> rdf:langString
<li>- rdfs:label &rarr;<sup>1 per language</sup> rdf:langString
<li>- schema:alternateName &rarr; rdf:langString
<li>- schema:image &rarr; xsd:anyURI
<li>- schema:mainEntityOfPage &rarr; xsd:anyURI
<li>- schema:sameAs &rarr; xsd:anyType
<li>- schema:url &rarr; xsd:anyURI
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:CreativeWork) schema:about<li>- (yago:Creator) schema:influencedBy<li>- (schema:Event) schema:about<li>- (schema:MusicGroup) schema:influencedBy<li>- (schema:Person) schema:owns</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:CreativeWork</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:about &rarr; schema:Thing
<li>- schema:author &rarr; schema:Organization, schema:Person
<li>- schema:award &rarr; yago:Award
<li>- schema:contentLocation &rarr; schema:Place
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:inLanguage &rarr; schema:Language
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (yago:Creator) yago:notableWork<li>- (yago:FictionalEntity) yago:appearsIn<li>- (schema:MusicGroup) yago:notableWork</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:Book</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:editor &rarr; schema:Person
<li>- schema:illustrator &rarr; schema:Person
<li>- schema:isbn &rarr;<sup>1</sup> xsd:string
<li>- schema:numberOfPages &rarr;<sup>1</sup> xsd:decimal
<li>- schema:publisher &rarr;<sup>1</sup> schema:Organization
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Movie</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:actor &rarr; schema:Person
<li>- schema:countryOfOrigin &rarr; schema:Country
<li>- schema:director &rarr; schema:Person
<li>- schema:duration &rarr;<sup>1</sup> xsd:decimal
<li>- schema:musicBy &rarr; schema:MusicGroup, schema:Person
<li>- schema:productionCompany &rarr; schema:Organization
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:MusicComposition</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:composer &rarr; schema:Person, schema:MusicGroup
<li>- schema:iswcCode &rarr;<sup>1</sup> xsd:string
<li>- schema:lyricist &rarr; schema:Person, schema:MusicGroup
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Newspaper</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:publisher &rarr; schema:Organization, schema:Person
<li>- schema:sponsor &rarr; schema:Organization, schema:Person
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:TVSeries</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:actor &rarr; schema:Person
<li>- schema:countryOfOrigin &rarr; schema:Country
<li>- schema:director &rarr; schema:Person
<li>- schema:musicBy &rarr; schema:MusicGroup, schema:Person
<li>- schema:numberOfEpisodes &rarr;<sup>1</sup> xsd:decimal
<li>- schema:numberOfSeasons &rarr;<sup>1</sup> xsd:decimal
<li>- schema:productionCompany &rarr; schema:Organization
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Event</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:about &rarr; schema:Thing
<li>- schema:endDate &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:location &rarr; schema:Place
<li>- schema:organizer &rarr; schema:Person, schema:Organization
<li>- schema:sponsor &rarr; schema:Organization, schema:Person
<li>- schema:startDate &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:superEvent &rarr; schema:Event
<li>- yago:follows &rarr; schema:Event
<li>- yago:participant &rarr; schema:Organization, schema:Person
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Event) yago:follows<li>- (schema:Event) schema:superEvent<li>- (yago:Politician) yago:candidateIn<li>- (yago:SportsPerson) yago:playsIn</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Intangible</summary><details><summary>Outgoing properties</summary><ul>
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:Language</summary><details><summary>Outgoing properties</summary><ul>
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Country) schema:officialLanguage<li>- (schema:CreativeWork) schema:inLanguage<li>- (schema:Person) schema:knowsLanguage</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>yago:Award</summary><details><summary>Outgoing properties</summary><ul>
<li>- yago:conferredBy &rarr; schema:Organization, schema:Person
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:CreativeWork) schema:award<li>- (schema:Organization) schema:award<li>- (schema:Person) yago:academicDegree<li>- (schema:Person) schema:award<li>- (schema:Product) schema:award</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>yago:Gender</summary><details><summary>Outgoing properties</summary><ul>
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Person) schema:gender</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Organization</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:address &rarr; xsd:string
<li>- schema:award &rarr; yago:Award
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:dissolutionDate &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:duns &rarr;<sup>1</sup> xsd:string
<li>- schema:founder &rarr; schema:Person
<li>- schema:leader &rarr; schema:Person
<li>- schema:leiCode &rarr;<sup>1</sup> xsd:string
<li>- schema:location &rarr; schema:Place
<li>- schema:locationCreated &rarr; schema:Place
<li>- schema:logo &rarr; xsd:anyURI
<li>- schema:memberOf &rarr; schema:Organization
<li>- schema:memberOf &rarr; schema:Organization
<li>- schema:motto &rarr; xsd:string
<li>- schema:numberOfEmployees &rarr; xsd:decimal
<li>- schema:parentOrganization &rarr; schema:Organization
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:AdministrativeArea) schema:memberOf<li>- (schema:Book) schema:publisher<li>- (schema:Movie) schema:productionCompany<li>- (schema:MusicGroup) schema:recordLabel<li>- (schema:Organization) schema:memberOf<li>- (schema:Organization) schema:memberOf<li>- (schema:Organization) schema:parentOrganization<li>- (schema:Person) schema:affiliation<li>- (schema:Person) schema:worksFor<li>- (schema:Person) schema:alumniOf<li>- (schema:Person) schema:memberOf<li>- (schema:TVSeries) schema:productionCompany</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:Airline</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:iataCode &rarr;<sup>1</sup> xsd:string
<li>- schema:icaoCode &rarr;<sup>1</sup> xsd:string
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Corporation</summary><details><summary>Outgoing properties</summary><ul>
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Product) schema:manufacturer</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:EducationalOrganization</summary><details><summary>Outgoing properties</summary><ul>
<li>- yago:studentsCount &rarr; xsd:decimal
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:PerformingGroup</summary><details><summary>Outgoing properties</summary><ul>
<li>- yago:director &rarr; schema:Person
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:MusicGroup</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:influencedBy &rarr; schema:Thing
<li>- schema:recordLabel &rarr; schema:Organization
<li>- yago:notableWork &rarr; schema:CreativeWork
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
</ul></details></details>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Person</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:affiliation &rarr; schema:Organization
<li>- schema:alumniOf &rarr; schema:Organization
<li>- schema:award &rarr; yago:Award
<li>- schema:birthDate &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:birthPlace &rarr;<sup>1</sup> schema:Place
<li>- schema:children &rarr; schema:Person
<li>- schema:deathDate &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:deathPlace &rarr;<sup>1</sup> schema:Place
<li>- schema:gender &rarr;<sup>1</sup> yago:Gender
<li>- schema:homeLocation &rarr; schema:Place
<li>- schema:knowsLanguage &rarr; schema:Language
<li>- schema:memberOf &rarr; schema:Organization
<li>- schema:nationality &rarr; schema:Country
<li>- schema:owns &rarr; schema:Thing
<li>- schema:spouse &rarr; schema:Person
<li>- schema:worksFor &rarr; schema:Organization
<li>- yago:academicDegree &rarr; yago:Award
<li>- yago:beliefSystem &rarr; yago:BeliefSystem
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (yago:Academic) yago:studentOf<li>- (yago:Academic) yago:doctoralAdvisor<li>- (schema:AdministrativeArea) schema:leader<li>- (schema:Book) schema:illustrator<li>- (schema:Book) schema:editor<li>- (yago:FictionalEntity) schema:performer<li>- (schema:Movie) schema:actor<li>- (schema:Movie) schema:director<li>- (schema:Organization) schema:founder<li>- (schema:Organization) schema:leader<li>- (schema:PerformingGroup) yago:director<li>- (schema:Person) schema:children<li>- (schema:Person) schema:spouse<li>- (schema:TVSeries) schema:actor<li>- (schema:TVSeries) schema:director</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Place</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:area &rarr; xsd:decimal
<li>- schema:containedInPlace &rarr; schema:Place
<li>- schema:elevation &rarr; xsd:decimal
<li>- schema:geo &rarr;<sup>1</sup> geo:wktLiteral
<li>- schema:highestPoint &rarr; schema:Place
<li>- schema:lowestPoint &rarr; schema:Place
<li>- schema:neighbors &rarr; schema:Place
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:AdministrativeArea) schema:administrates<li>- (schema:CreativeWork) schema:contentLocation<li>- (schema:Event) schema:location<li>- (schema:Organization) schema:locationCreated<li>- (schema:Organization) schema:location<li>- (schema:Person) schema:birthPlace<li>- (schema:Person) schema:deathPlace<li>- (schema:Person) schema:homeLocation<li>- (schema:Place) schema:containedInPlace<li>- (schema:Place) schema:highestPoint<li>- (schema:Place) schema:lowestPoint<li>- (schema:Place) schema:neighbors<li>- (yago:Way) yago:terminus</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:AdministrativeArea</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:administrates &rarr; schema:Place
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime
<li>- schema:demonym &rarr; xsd:string
<li>- schema:leader &rarr; schema:Person
<li>- schema:memberOf &rarr; schema:Organization
<li>- schema:motto &rarr; xsd:string
<li>- schema:populationNumber &rarr; xsd:decimal
<li>- schema:postalCode &rarr; xsd:string
<li>- yago:replaces &rarr; schema:AdministrativeArea
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:AdministrativeArea) yago:replaces</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:City</summary><details><summary>Outgoing properties</summary><ul>
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Country) yago:capital</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Country</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:humanDevelopmentIndex &rarr; xsd:decimal
<li>- schema:officialLanguage &rarr; schema:Language
<li>- schema:unemploymentRate &rarr; xsd:decimal
<li>- yago:capital &rarr; schema:City
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Movie) schema:countryOfOrigin<li>- (schema:Person) schema:nationality<li>- (schema:TVSeries) schema:countryOfOrigin</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Landform</summary><details><summary>Outgoing properties</summary><ul>
</ul></details>
<details><summary>Incoming properties</summary><ul>
</ul></details><details><summary>Subclasses</summary><ul>
<li><details><summary class='yagoClass'>schema:BodyOfWater</summary><details><summary>Outgoing properties</summary><ul>
<li>- yago:flowsInto &rarr; schema:BodyOfWater
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:BodyOfWater) yago:flowsInto</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
</ul></details></details>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Product</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:award &rarr; yago:Award
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:gtin &rarr; xsd:string
<li>- schema:manufacturer &rarr; schema:Corporation
<li>- schema:material &rarr; schema:Product
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Product) schema:material</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
<li><details><summary class='yagoClass'>schema:Taxon</summary><details><summary>Outgoing properties</summary><ul>
<li>- schema:parentTaxon &rarr; schema:Taxon
<li>- yago:consumes &rarr; schema:Taxon
</ul></details>
<details><summary>Incoming properties</summary><ul>
<li>- (schema:Taxon) schema:parentTaxon<li>- (schema:Taxon) yago:consumes</ul></details><details><summary>Subclasses</summary><ul>
</ul></details></details>
</ul></details></details>
</ul>