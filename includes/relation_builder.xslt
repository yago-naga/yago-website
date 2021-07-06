<?xml version="1.0" encoding="UTF-8"?>
<!--- Transforms a SPARQL query result about an entity and a relation into an SVG visualization. The query takes the following form:

SELECT ?s ?p ?o ('3' AS ?page) ('0' AS ?inverse) (<http://www.w3.org/2000/01/rdf-schema#label> AS ?relation) WHERE {
     BIND(<http://yago-knowledge.org/resource/Elvis_Presley> AS ?s)
     BIND(<http://www.w3.org/2000/01/rdf-schema#label> AS ?p)
     ?s ?p ?o .     
} LIMIT 20 OFFSET 40

... or...

SELECT ?s ?p ?o ('3' AS ?page) ('1' AS ?inverse) (<http://www.w3.org/2000/01/rdf-schema#label> AS ?relation) WHERE {
     BIND(<http://yago-knowledge.org/resource/Elvis_Presley> AS ?s)
     BIND(<http://www.w3.org/2000/01/rdf-schema#label> AS ?p)
     ?o ?p ?s .     
} LIMIT 20 OFFSET 40

Here, the ?relation is either a YAGO relation (if the ?p are all the same), or the keyword 'all' (if the ?p are different relations). The variable ?inverse decides whether incoming our outgoing links should be shown.

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
		<!-- Decide the height of the SVG: If we just have 2 objects, we can show a thin SVG -->
		<xsl:variable name="objects" select="/s:sparql/s:results/s:result" />
		<xsl:variable name="numberOfObjects" select="count($objects)" />				
		<xsl:variable name="myHeight">				
			<xsl:if test="$numberOfObjects&lt;3"><xsl:value-of select="$fontSize*10"/></xsl:if>
			<xsl:if test="$numberOfObjects&gt;2"><xsl:value-of select="$height"/></xsl:if>
		</xsl:variable>
		<xsl:variable name="x" select="$width div 2" />
		<xsl:variable name="y" select="$myHeight div 2" />

		<svg
			xmlns="http://www.w3.org/2000/svg"
			xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 {$width} {$myHeight}">
			<defs>
				<marker id="mblack" markerHeight="3" markerUnits="strokeWidth" markerWidth="4" orient="auto" refX="0" refY="5" viewBox="0 0 10 10">
					<path d="M 0 0 L 10 5 L 0 10 z" fill="black" stroke="black"/>
				</marker>
			</defs>
			
			<!-- If we have no results, say so -->
			<xsl:if test="$numberOfObjects=0">
				<text text-anchor="middle" x="{$x}" y="{$y}"  font-size="{$fontSize}">No results...</text>
			</xsl:if>
			<xsl:if test="$numberOfObjects&gt;0">
		
				<!-- Print the entity name -->
				<xsl:variable name="entity" select="/s:sparql/s:results/s:result[1]/s:binding[@name='s']" />
				<text text-anchor="middle" x="{$x}" y="{$y + $fontSize*0.2}" font-size="{$fontSize}">
					<xsl:call-template name="printObject">
						<xsl:with-param name="object" select="$entity" />		
						<xsl:with-param name="length" select="$maxSubjectDisplayLength" />		
					</xsl:call-template>
				</text>

				<!-- Get the objects -->
				<xsl:variable name="page" select="number(/s:sparql/s:results/s:result[1]/s:binding[@name='page']/s:literal/text())" />
				<xsl:variable name="relation" select="/s:sparql/s:results/s:result[1]/s:binding[@name='relation']/s:literal/text()" />
				<xsl:variable name="inverse" select="number(/s:sparql/s:results/s:result[1]/s:binding[@name='inverse']/s:literal/text())" />

				<!-- Print the page number -->
				<text text-anchor="end" x="{$width - $fontSize}" y="{$myHeight - $fontSize}" font-size="{$fontSize}">Page&#8239;<xsl:value-of select="$page + 1" />&#8239;
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
					<xsl:variable name="circlePosition">
					   <xsl:if test="position()&lt;=$numberOfObjects div 2 or position()=1"><xsl:value-of select="(position() - 1) div ($numberOfObjects div 2)" /></xsl:if>					   
					   <xsl:if test="position()&gt;$numberOfObjects div 2 and position()!=1"><xsl:value-of select="(position() - floor($numberOfObjects div 2) - 1) div ($numberOfObjects div 2)" /></xsl:if>					   
					</xsl:variable>
					<xsl:variable name="distanceFactor" select="($circlePosition - 0.5) * ($circlePosition - 0.5)" />					
					<xsl:if test="not($inverse)">
						<line x1="{$x + $maxSubjectDisplayLength * $fontSize * 0.1 + $distanceFactor * $fontSize * 20}" y1="{$y}" x2="{$x+$radius}" y2="{$y}" transform="rotate({360 div $numberOfObjects * (position() - 1)} {$x} {$y})" marker-end="url(#mblack)" stroke-width="{$fontSize*0.1}" stroke="black" />
					</xsl:if>
					<xsl:if test="$inverse">
						<line x1="{$x+$radius}" y1="{$y}" x2="{$x + $maxSubjectDisplayLength * $fontSize * 0.1 + $distanceFactor * $fontSize * 20}" y2="{$y}" transform="rotate({360 div $numberOfObjects * (position() - 1)} {$x} {$y})" marker-end="url(#mblack)" stroke-width="{$fontSize*0.1}" stroke="black" />
					</xsl:if>

					<!-- Treat left and right half differently -->
					<xsl:choose>
						<xsl:when test="(position()&lt;= $numberOfObjects div 4 + 1) or (position()&gt; $numberOfObjects div 4 * 3 + 2)">
								<text x="{$x+$fontSize+$radius}" y="{$y+$fontSize*0.2}" transform="rotate({360 div $numberOfObjects * (position() - 1)} {$x} {$y})" font-size="{$fontSize}">
									<xsl:call-template name="printObject">
										<xsl:with-param name="object" select="s:binding[@name='o']" />		
										<xsl:with-param name="length" select="$maxObjectDisplayLength" />										
									</xsl:call-template>
								</text>
							<text text-anchor="end" x="{$x+$radius - ($fontSize div 2)}" y="{$y - $fontSize*0.2}" transform="rotate({360 div $numberOfObjects * (position() - 1)} {$x} {$y})" font-size="{$fontSize}">
								<xsl:call-template name="printString">
									<xsl:with-param name="object" select="s:binding[@name='p']" />						
								</xsl:call-template>																
							</text>								
						</xsl:when>
						<xsl:otherwise>
					  	   <text text-anchor="end" x="{$x - $radius - $fontSize}" y="{$y+$fontSize*0.2}" transform="rotate({(position() - ($numberOfObjects div 2 + 1)) * (360 div $numberOfObjects)} {$x} {$y})" font-size="{$fontSize}"  fill="black">
								<xsl:call-template name="printObject">
									<xsl:with-param name="object" select="s:binding[@name='o']" />	
									<xsl:with-param name="length" select="$maxObjectDisplayLength" />											
								</xsl:call-template>
							</text>
							<text x="{$x - $radius + $fontSize*0.5}" y="{$y - $fontSize*0.2}" transform="rotate({(position() - ($numberOfObjects div 2 + 1)) * (360 div $numberOfObjects)} {$x} {$y})" font-size="{$fontSize}"  fill="black">
								<xsl:call-template name="printString">
									<xsl:with-param name="object" select="s:binding[@name='p']" />						
								</xsl:call-template>								
							</text>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:for-each>
			</xsl:if>
		</svg>
	</xsl:template>
</xsl:stylesheet>
