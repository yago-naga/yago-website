<?xml version="1.0" encoding="UTF-8"?>
<!--- Transforms a SPARQL query result about an entity and a relation into an SVG visualization. The query takes the following form:

SELECT ?s ?p ?o ('3' AS ?page) ('0' AS ?inverse) (<http://schema.org/name> AS ?relation) WHERE {
     BIND(<http://yago-knowledge.org/resource/Elvis_Presley> AS ?s)
     BIND(<http://schema.org/name> AS ?p)
     ?s ?p ?o .     
} LIMIT 20 OFFSET 40

... or...

SELECT ?s ?p ?o ('3' AS ?page) ('1' AS ?inverse) (<http://schema.org/name> AS ?relation) WHERE {
     BIND(<http://yago-knowledge.org/resource/Elvis_Presley> AS ?s)
     BIND(<http://schema.org/name> AS ?p)
     ?o ?p ?s .     
} LIMIT 20 OFFSET 40

Here, the ?relation is either a YAGO relation (if the ?p are all the same), or the keyword 'all' (if the ?p are different relations).

http://yago.r2.enst.fr/sparql/query?query=SELECT%20%3Fs%20%3Fp%20%3Fo%20('3'%20AS%20%3Fpage)%20('1'%20AS%20%3Finverse)%20WHERE%20{BIND(<http://yago-knowledge.org/resource/Elvis_Presley>%20AS%20%3Fs)BIND(<http://schema.org/name>%20AS%20%3Fp)%20%3Fs%20%3Fp%20%3Fo%20.}%20LIMIT%2020%20OFFSET%2040

-->

<!-- We need the node-set operator, which is either in the Microsoft namespace or in the EXSLT namespace, depending on the XSLT processor you use. Rename the prefix to "ex" for the namespace that corresponds to your processor. -->
<xsl:stylesheet
	xmlns="http://www.w3.org/2000/svg"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:ex_="urn:schemas-microsoft-com:xslt"
	xmlns:s="http://www.w3.org/2005/sparql-results#"
	xmlns:svg="http://www.w3.org/2000/svg"
	xmlns:ex="http://exslt.org/common" version="1.0" exclude-result-prefixes="xsl s svg ex ex_">

    <xsl:import href="builder_config.xslt" />

    <!-- Main template -->
	<xsl:template match="/">
		<svg
			xmlns="http://www.w3.org/2000/svg"
			xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 {$width} {$height}">
			<defs>
				<marker id="mblack" markerHeight="3" markerUnits="strokeWidth" markerWidth="4" orient="auto" refX="0" refY="5" viewBox="0 0 10 10">
					<path d="M 0 0 L 10 5 L 0 10 z" fill="black" stroke="black"/>
				</marker>
			</defs>
			 
				<!-- Print the entity name -->
				<xsl:variable name="x" select="$width div 2" />
				<xsl:variable name="y" select="$height div 2" />
				<xsl:variable name="entity" select="/s:sparql/s:results/s:result[1]/s:binding[@name='s']" />
				<text text-anchor="middle" x="{$x}" y="{$y}" font-size="{$fontSize}">
					<xsl:call-template name="printObject">
						<xsl:with-param name="object" select="$entity" />		
					</xsl:call-template>
				</text>

				<!-- Get the objects -->
				<xsl:variable name="objects" select="/s:sparql/s:results/s:result" />
				<xsl:variable name="numberOfObjects" select="count($objects)" />				
				<xsl:variable name="page" select="number(/s:sparql/s:results/s:result[1]/s:binding[@name='page']/s:literal/text())" />
				<xsl:variable name="relation" select="/s:sparql/s:results/s:result[1]/s:binding[@name='relation']/s:literal/text()" />
				<xsl:variable name="inverse" select="number(/s:sparql/s:results/s:result[1]/s:binding[@name='inverse']/s:literal/text())" />

				<!-- Print the page number -->
				<text text-anchor="end" x="{$width - $fontSize}" y="{$height - $fontSize}" font-size="{$fontSize}">Page&#8239;<xsl:value-of select="$page + 1" />&#8239;
				<xsl:if test="$numberOfObjects=20">
					<xsl:if test="$entity/s:uri">
						<a href="{concat($yagoUrl,$entity,'?relation=',$relation,'&amp;cursor=',$page + 1,'&amp;inverse=',$inverse)}" style="fill: blue">(more)</a>
					</xsl:if>
					<xsl:if test="$entity/s:literal">
						<a href="{concat($yagoUrl,'&quot;',$entity,'&quot;',substring(concat('@',$entity/s:literal/@xml:lang),number(not(boolean($entity/s:literal/@xml:lang)))*100),substring(concat('^^',$entity/s:literal/@datatype),number(not(boolean($entity/s:literal/@datatype)))*1000),'?relation=',$relation,'&amp;cursor=',$page + 1,'&amp;inverse=',$inverse)}" style="fill: blue">(more)</a>
					</xsl:if>
				</xsl:if>
			    </text>

				<!-- Print the facts -->
				<xsl:for-each select="$objects">
					
					<!-- Draw the arrow -->
					<xsl:if test="not($inverse)">
						<line x1="{$x+ $fontSize*$maxPredicateDisplayLength*0.5 div 2}" y1="{$y}" x2="{$x+$radius}" y2="{$y}" transform="rotate({360 div $numberOfObjects * position() - 90} {$x} {$y})" marker-end="url(#mblack)" stroke-width="{$fontSize*0.1}" stroke="black" />
					</xsl:if>
					<xsl:if test="$inverse">
						<line x1="{$x+$radius}" y1="{$y}" x2="{$x+ $fontSize*$maxPredicateDisplayLength*0.5 div 2}" y2="{$y}" transform="rotate({360 div $numberOfObjects * position() - 90} {$x} {$y})" marker-end="url(#mblack)" stroke-width="{$fontSize*0.1}" stroke="black" />
					</xsl:if>

					<!-- Treat left and right half differently -->
					<xsl:choose>
						<xsl:when test="position()&lt; $numberOfObjects div 2">
								<text x="{$x+$fontSize+$radius}" y="{$y+$fontSize*0.3}" transform="rotate({360 div $numberOfObjects * position() - 90} {$x} {$y})" font-size="{$fontSize}">
									<xsl:call-template name="printObject">
										<xsl:with-param name="object" select="s:binding[@name='o']" />		
										<xsl:with-param name="length" select="$maxEntityDisplayLength" />										
									</xsl:call-template>
								</text>
							<text text-anchor="end" x="{$x+$radius - ($fontSize div 2)}" y="{$y - $fontSize*0.2}" transform="rotate({360 div $numberOfObjects * position() - 90} {$x} {$y})" font-size="{$fontSize}">
								<xsl:call-template name="printObject">
									<xsl:with-param name="object" select="s:binding[@name='p']" />						
								</xsl:call-template>																
							</text>								
						</xsl:when>
						<xsl:otherwise>
							<text text-anchor="end" x="{$x - $radius - $fontSize}" y="{$y+$fontSize*0.3}" transform="rotate({-360 div $numberOfObjects * ($numberOfObjects - position()) + 90} {$x} {$y})" font-size="{$fontSize}"  fill="black">
								<xsl:call-template name="printObject">
									<xsl:with-param name="object" select="s:binding[@name='o']" />	
									<xsl:with-param name="length" select="$maxEntityDisplayLength" />											
								</xsl:call-template>
							</text>
							<text x="{$x - $radius + $fontSize*0.5}" y="{$y - $fontSize*0.2}" transform="rotate({-360 div $numberOfObjects  * ($numberOfObjects - position())  + 90} {$x} {$y})" font-size="{$fontSize}"  fill="black">
								<xsl:call-template name="printObject">
									<xsl:with-param name="object" select="s:binding[@name='p']" />						
								</xsl:call-template>								
							</text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:for-each>

		</svg>
	</xsl:template>
</xsl:stylesheet>
