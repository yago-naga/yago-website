
<h1>Schema</h1>

This is the top-level taxonomy of classes of YAGO. Please find detailed descriptions in our <a href="https://yago-knowledge.org/data/yago4.5/design-document.pdf">Design Document</a>.

 <ul style='list-style-type: none'>
        <li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Thing</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- rdfs:comment &rarr;<sup>1 per language</sup> rdf:langString
<li>- rdfs:label &rarr;<sup>1 per language</sup> rdf:langString
<li>- schema:alternateName &rarr; rdf:langString
<li>- schema:image &rarr; xsd:anyURI
<li>- schema:mainEntityOfPage &rarr; xsd:anyURI
<li>- schema:sameAs &rarr; xsd:anyURI
<li>- schema:url &rarr; xsd:anyURI
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:CreativeWork) schema:about<li>- (yago:Creator) schema:influencedBy<li>- (schema:Event) schema:about<li>- (schema:MusicGroup) schema:influencedBy<li>- (schema:Person) schema:owns</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:CreativeWork</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:about &rarr; schema:Thing
<li>- schema:author &rarr; schema:Organization, schema:Person
<li>- schema:award &rarr; yago:Award
<li>- schema:contentLocation &rarr; schema:Place
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:inLanguage &rarr; schema:Language
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (yago:Creator) yago:notableWork<li>- (yago:FictionalEntity) yago:appearsIn<li>- (schema:PerformingGroup) yago:notableWork</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Book</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:editor &rarr; schema:Person
<li>- schema:illustrator &rarr; schema:Person
<li>- schema:isbn &rarr;<sup>1</sup> xsd:string
<li>- schema:numberOfPages &rarr;<sup>1</sup> xsd:decimal
<li>- schema:publisher &rarr;<sup>1</sup> schema:Organization, schema:Person
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Movie</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:actor &rarr; schema:Person
<li>- schema:director &rarr; schema:Person
<li>- schema:duration &rarr;<sup>1</sup> xsd:decimal
<li>- schema:locationCreated &rarr; schema:Place
<li>- schema:musicBy &rarr; schema:MusicGroup, schema:Person
<li>- schema:productionCompany &rarr; schema:Organization
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:MusicComposition</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:iswcCode &rarr;<sup>1</sup> xsd:string
<li>- schema:lyricist &rarr; schema:Person, schema:MusicGroup
<li>- schema:musicBy &rarr; schema:Person, schema:MusicGroup
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Newspaper</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:publisher &rarr;<sup>1</sup> schema:Organization, schema:Person
<li>- schema:sponsor &rarr; schema:Organization, schema:Person
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:TVSeries</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:actor &rarr; schema:Person
<li>- schema:director &rarr; schema:Person
<li>- schema:locationCreated &rarr; schema:Place
<li>- schema:musicBy &rarr; schema:MusicGroup, schema:Person
<li>- schema:numberOfEpisodes &rarr;<sup>1</sup> xsd:decimal
<li>- schema:numberOfSeasons &rarr;<sup>1</sup> xsd:decimal
<li>- schema:productionCompany &rarr; schema:Organization
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Event</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
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
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Event) yago:follows<li>- (schema:Event) schema:superEvent<li>- (yago:Politician) yago:candidateIn<li>- (yago:SportsPerson) yago:playsIn</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Election</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Intangible</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Language</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Country) schema:officialLanguage<li>- (schema:CreativeWork) schema:inLanguage<li>- (schema:PerformingGroup) schema:knowsLanguage<li>- (schema:Person) schema:knowsLanguage</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Award</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:conferredBy &rarr; schema:Organization, schema:Person
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:CreativeWork) schema:award<li>- (schema:Organization) schema:award<li>- (schema:Person) yago:academicDegree<li>- (schema:Person) schema:award<li>- (schema:Product) schema:award</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:BeliefSystem</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Person) yago:beliefSystem</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Gender</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Person) schema:gender</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Organization</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
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
<li>- schema:motto &rarr; xsd:string
<li>- schema:numberOfEmployees &rarr; xsd:decimal
<li>- schema:ownedBy &rarr; schema:Organization, schema:Person
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:AdministrativeArea) schema:memberOf<li>- (schema:Movie) schema:productionCompany<li>- (schema:MusicGroup) schema:recordLabel<li>- (schema:Organization) schema:memberOf<li>- (schema:Person) schema:affiliation<li>- (schema:Person) schema:worksFor<li>- (schema:Person) schema:alumniOf<li>- (schema:Person) schema:memberOf<li>- (schema:TVSeries) schema:productionCompany</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Corporation</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Product) schema:manufacturer</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Airline</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:iataCode &rarr;<sup>1</sup> xsd:string
<li>- schema:icaoCode &rarr;<sup>1</sup> xsd:string
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:EducationalOrganization</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:studentsCount &rarr; xsd:decimal
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:PerformingGroup</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:knowsLanguage &rarr; schema:Language
<li>- yago:director &rarr; schema:Person
<li>- yago:notableWork &rarr; schema:CreativeWork
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:MusicGroup</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:influencedBy &rarr; schema:Thing
<li>- schema:recordLabel &rarr; schema:Organization
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Person</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
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
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (yago:Academic) yago:studentOf<li>- (yago:Academic) yago:doctoralAdvisor<li>- (schema:AdministrativeArea) schema:leader<li>- (schema:Book) schema:illustrator<li>- (schema:Book) schema:editor<li>- (yago:FictionalEntity) schema:performer<li>- (schema:Movie) schema:actor<li>- (schema:Movie) schema:director<li>- (schema:Organization) schema:founder<li>- (schema:Organization) schema:leader<li>- (schema:PerformingGroup) yago:director<li>- (schema:Person) schema:children<li>- (schema:Person) schema:spouse<li>- (schema:TVSeries) schema:actor<li>- (schema:TVSeries) schema:director</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Worker</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Academic</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:doctoralAdvisor &rarr; schema:Person
<li>- yago:studentOf &rarr; schema:Person
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Creator</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:influencedBy &rarr; schema:Thing
<li>- yago:notableWork &rarr; schema:CreativeWork
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Politician</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:candidateIn &rarr; schema:Event
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:SportsPerson</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:playsIn &rarr; schema:Event
<li>- yago:sportNumber &rarr; xsd:string
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Place</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:area &rarr; xsd:decimal
<li>- schema:elevation &rarr; xsd:decimal
<li>- schema:geo &rarr;<sup>1</sup> geo:wktLiteral
<li>- schema:highestPoint &rarr; schema:Place
<li>- schema:location &rarr; schema:Place
<li>- schema:lowestPoint &rarr; schema:Place
<li>- schema:neighbors &rarr; schema:Place
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:AdministrativeArea) schema:administrates<li>- (schema:CreativeWork) schema:contentLocation<li>- (schema:Event) schema:location<li>- (schema:Movie) schema:locationCreated<li>- (schema:Organization) schema:locationCreated<li>- (schema:Organization) schema:location<li>- (schema:Person) schema:birthPlace<li>- (schema:Person) schema:deathPlace<li>- (schema:Person) schema:homeLocation<li>- (schema:Place) schema:location<li>- (schema:Place) schema:highestPoint<li>- (schema:Place) schema:lowestPoint<li>- (schema:Place) schema:neighbors<li>- (schema:TVSeries) schema:locationCreated<li>- (yago:Way) yago:terminus</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:AdministrativeArea</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:administrates &rarr; schema:Place
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime
<li>- schema:demonym &rarr; xsd:string
<li>- schema:leader &rarr; schema:Person
<li>- schema:memberOf &rarr; schema:Organization
<li>- schema:motto &rarr; xsd:string
<li>- schema:populationNumber &rarr; xsd:decimal
<li>- schema:postalCode &rarr; xsd:string
<li>- yago:capital &rarr; schema:City
<li>- yago:replaces &rarr; schema:AdministrativeArea
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:AdministrativeArea) yago:replaces</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:City</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:AdministrativeArea) yago:capital</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Country</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:humanDevelopmentIndex &rarr; xsd:decimal
<li>- schema:officialLanguage &rarr; schema:Language
<li>- schema:unemploymentRate &rarr; xsd:decimal
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Person) schema:nationality</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Landform</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:BodyOfWater</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:flowsInto &rarr; schema:BodyOfWater
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:BodyOfWater) yago:flowsInto</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Continent</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:AstronomicalObject</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:distanceFromEarth &rarr; xsd:decimal
<li>- yago:luminosity &rarr; xsd:decimal
<li>- yago:mass &rarr; xsd:decimal
<li>- yago:parallax &rarr; xsd:decimal
<li>- yago:parentBody &rarr; yago:AstronomicalObject
<li>- yago:radialVelocity &rarr; xsd:decimal
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (yago:AstronomicalObject) yago:parentBody</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:HumanMadeGeographicalEntity</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime
<li>- schema:ownedBy &rarr; schema:Organization, schema:Person
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Airport</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:iataCode &rarr;<sup>1</sup> xsd:string
<li>- schema:icaoCode &rarr;<sup>1</sup> xsd:string
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:Way</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- yago:length &rarr;<sup>1</sup> xsd:decimal
<li>- yago:terminus &rarr; schema:Place
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Product</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:award &rarr; yago:Award
<li>- schema:dateCreated &rarr;<sup>1</sup> xsd:dateTime, xsd:date, xsd:gYearMonth, xsd:gYear
<li>- schema:gtin &rarr; xsd:string
<li>- schema:manufacturer &rarr; schema:Corporation
<li>- schema:material &rarr; schema:Product
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Product) schema:material</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>schema:Taxon</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:parentTaxon &rarr; schema:Taxon
<li>- yago:consumes &rarr; schema:Taxon
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
<li>- (schema:Taxon) schema:parentTaxon<li>- (schema:Taxon) yago:consumes</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
<li><details style='margin-left: 2em'><summary style='font-weight:bold; margin-left: -2em'>yago:FictionalEntity</summary><details style='margin-left: 2em'><summary style='margin-left: -2em'>Outgoing properties</summary><ul style='list-style-type: none'>
<li>- schema:author &rarr; schema:Organization, schema:Person
<li>- schema:performer &rarr; schema:Person
<li>- yago:appearsIn &rarr; schema:CreativeWork
</ul></details>
<details style='margin-left: 2em'><summary style='margin-left: -2em'>Incoming properties</summary><ul style='list-style-type: none'>
</ul></details><details style='margin-left: 2em'><summary style='margin-left: -2em'>Subclasses</summary><ul style='list-style-type: none'>
</ul></details></details>
</ul></details></details>
</ul>