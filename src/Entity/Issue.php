<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ActivityLogBundle\Entity\Interfaces\StringableInterface;

/**
 * Issue
 * 
 * Beschrijving
 * 
 * @category   	Entity
 *
 * @author     	Ruben van der Linde <ruben@conduction.nl>
 * @license    	EUPL 1.2 https://opensource.org/licenses/EUPL-1.2 *
 * @version    	1.0
 *
 * @link   		http//:www.conduction.nl
 * @package		Common Ground
 * @subpackage  Issue
 * 
 *  @ApiResource( 
 *  collectionOperations={
 *  	"get"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/issues",
 *  		"openapi_context" = {
 * 				"summary" = "Haalt een verzameling van issues op"
 *  		}
 *  	},
 *  	"post"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/issues",
 *  		"openapi_context" = {
 * 					"summary" = "Maak een issue aan"
 *  		}
 *  	}
 *  },
 * 	itemOperations={
 *     "get"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/issues/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Haal een specifiek issue op"
 *  		}
 *  	},
 *     "put"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/issues/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Vervang een specifiek issue"
 *  		}
 *  	},
 *     "delete"={
 *  		"normalizationContext"={"groups"={"read"}},
 *  		"denormalizationContext"={"groups"={"write"}},
 *      	"path"="/issues/{id}",
 *  		"openapi_context" = {
 * 				"summary" = "Verwijder een specifiek issue"
 *  		}
 *  	},
 *  }
 * )
 * @ORM\Entity
 * @Gedmo\Loggable(logEntryClass="ActivityLogBundle\Entity\LogEntry")
 * @ORM\HasLifecycleCallbacks
 */
class Issue implements StringableInterface
{
	/**
	 * Het identificatie nummer van dit Issue <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var int|null
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer", options={"unsigned": true})
	 * @Groups({"read", "write"})
	 * @ApiProperty(iri="https://schema.org/identifier", identifier=true)
	 */
	public $id;
	
	/**
	 * De unieke identificatie van dit object binnen de organisatie die dit object heeft gecreeerd. <br /><b>Schema:</b> <a href="https://schema.org/identifier">https://schema.org/identifier</a>
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 40,
	 *     nullable=true
	 * )
	 * @Assert\Length(
	 *      max = 40,
	 *      maxMessage = "Het RSIN kan niet langer dan {{ limit }} karakters zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="6a36c2c4-213e-4348-a467-dfa3a30f64aa",
	 *             "description"="De unieke identificatie van dit object de organisatie die dit object heeft gecreeerd.",
	 *             "maxLength"=40
	 *         }
	 *     }
	 * )
	 * @Gedmo\Versioned
	 */
	public $identificatie;
	
	/**
	 * De organisatie waartoe dit issue behoord
	 *
	 * @var \App\Entity\Organisatie
	 * @ORM\ManyToOne(targetEntity="\App\Entity\Organisatie", cascade={"persist", "remove"}, inversedBy="issues")
	 * @ORM\JoinColumn(referencedColumnName="id")
	 *
	 */
	public $bronOrganisatie;
		
	/**
	 * Het type van dit issue <br /><b>Schema:</b> <a href="https://schema.org/additionalType">https://schema.org/additionalType</a>
	 *
	 * @var string
	 * @Assert\Choice({"contact", "aanvraag"})
	 * @ORM\Column(
	 *     type     = "string"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "enum"={"contact", "aanvraag"},
	 *             "example"="contact",
	 *             "default"="contact"
	 *         }
	 *     }
	 * )
	 * @Groups({"read", "write"})
	 */
	public $type = "contact";
	
	/**
	 * De naam van deze locatie <br /><b>Schema:</b> <a href="https://schema.org/name">https://schema.org/name</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 255, 
	 *     nullable=true
	 * )
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      min = 5,
	 *      max = 255,
	 *      minMessage = "De naam moet ten minste {{ limit }} tekens lang zijn",
	 *      maxMessage = "De naam kan niet langer dan {{ limit }} tekens zijn"
	 * )
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 * 	   iri="http://schema.org/name",
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="Gratis trouwen"
	 *         }
	 *     }
	 * )
	 **/
	public $naam;
	
	/**
	 * Een beschrijvende tekst over deze locatie  <br /><b>Schema:</b> <a href="https://schema.org/description">https://schema.org/description</a>
	 *
	 * @var string
	 *
	 * @ORM\Column(
	 *     type     = "text", 
	 *     nullable=true
	 * )
	 * @Assert\NotNull
	 * @Assert\Length(
	 *      min = 25,
	 *      max = 2000,
	 *      minMessage = "De beschrijving van uw probleem moet minimaal {{ limit }} tekens lang zijn",
	 *      maxMessage = "De beschrijving van uw probleem kan nietlanger zijn dan {{ limit }} tekens")
	 *
	 * @Groups({"read", "write"})
	 * @ApiProperty(
	 * 	  iri="https://schema.org/description",
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="Deze prachtige locatie is zeker het aanbevelen waard"
	 *         }
	 *     }
	 * )
	 **/
	public $beschrijving;	
	
	/**
	 * Het emailadres van de persoon die je probeert te uit te nodigen. <br /><b>Schema:</b> <a href="https://schema.org/givenName">https://schema.org/givenName</a>
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "text", 
	 *     nullable=true
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="John",
	 *             "required"="true",
	 *             "maxLength"=250,
	 *             "readOnly"=false
	 *         }
	 *     }
	 * )
	 * @Groups({"read","write"})
	 */
	public $voornamen;
	
	/**
	 * De familienaam van de persoon die je probeert uit te nodigen. <br /><b>Schema:</b> <a href="https://schema.org/familyName">https://schema.org/familyName</a>
	 *
	 * @var string
	 * @ORM\Column(
	 *     type     = "text", 
	 *     nullable=true
	 * )
	 * @ApiProperty(
	 *     attributes={
	 *         "openapi_context"={
	 *             "type"="string",
	 *             "example"="Do",
	 *             "required"="true",
	 *             "maxLength"=250,
	 *             "readOnly"=false
	 *         }
	 *     }
	 * )
	 * @Groups({"read","write"})
	 */
	public $geslachtsnaam;	
	
	/**
	 * De familienaam van de persoon die je probeert uit te nodigen. <br /><b>Schema:</b> <a href="https://schema.org/familyName">https://schema.org/familyName</a>
	* @todo zou emailadres moeten zijn
	* @Groups({"read","write"})
	*/
	public $email;	
	
	/**
	 * De familienaam van de persoon die je probeert uit te nodigen. <br /><b>Schema:</b> <a href="https://schema.org/familyName">https://schema.org/familyName</a>
	 *
	 * @Groups({"read","write"})
	 */
	public $telefoonnummer;	
	
	/**
	 * Datum waarop dit product geregistreerd is
	 *
	 * @var string Een "Y-m-d H:i:s" waarde bijv. "2018-12-31 13:33:05" ofwel "Jaar-dag-maan uur:minut:seconde"
	 * @Gedmo\Timestampable(on="create")
	 * @Assert\DateTime
	 * @ORM\Column(
	 *     type     = "datetime"
	 * )
	 * @Groups({"read"})
	 */
	public $registratieDatum;
	
	/**
	 * De taal waarin de informatie van deze locatie is opgesteld <br /><b>Schema:</b> <a href="https://www.ietf.org/rfc/rfc3066.txt">https://www.ietf.org/rfc/rfc3066.txt</a>
	 *
	 * @var string Een Unicode language identifier, ofwel RFC 3066 taalcode.
	 *
	 * @ORM\Column(
	 *     type     = "string",
	 *     length   = 2
	 * )
	 * @Groups({"read", "write"})
	 * @Assert\Language
	 * @ApiProperty(
	 *     attributes={
	 *         "swagger_context"={
	 *             "type"="string",
	 *             "example"="NL"
	 *         }
	 *     }
	 * )
	 **/
	public $taal = 'nl';
	
	/**
	 * @return string
	 */
	public function toString(){
		return printf("Issue: %s.",$this->id);
	}
	
	/**
	 * Vanuit rendering perspectief (voor bijvoorbeeld logging of berichten) is het belangrijk dat we een entiteit altijd naar string kunnen omzetten
	 */
	public function __toString()
	{
		return $this->toString();
	}
	
	/**
	 * The pre persist function is called when the enity is first saved to the database and allows us to set some aditional first values
	 *
	 * @ORM\PrePersist
	 */
	public function prePersist()
	{
		$this->registratieDatum = new \ Datetime();
		// We want to add some default stuff here like products, productgroups, paymentproviders, templates, clientGroups, mailinglists and ledgers
		return $this;
	}
}
