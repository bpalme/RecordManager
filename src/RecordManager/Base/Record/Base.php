<?php
/**
 * Base class for record drivers
 *
 * PHP version 5
 *
 * Copyright (C) The National Library of Finland 2011-2017.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category DataManagement
 * @package  RecordManager
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/KDK-Alli/RecordManager
 */
namespace RecordManager\Base\Record;

use RecordManager\Base\Utils\Logger;
use RecordManager\Base\Utils\MetadataUtils;

/**
 * Base class for record drivers
 *
 * This is a base class for processing records.
 *
 * @category DataManagement
 * @package  RecordManager
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/KDK-Alli/RecordManager
 */
class Base
{
    /**
     * Logger
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Main configuration
     *
     * @var array
     */
    protected $config;

    /**
     * Data source settings
     *
     * @var array
     */
    protected $dataSourceSettings;

    /**
     * Record source ID
     *
     * @var string
     */
    protected $source;

    /**
     * Record ID prefix
     *
     * @var string
     */
    protected $idPrefix = '';

    /**
     * Warnings about problems in the record
     *
     * @var array
     */
    protected $warnings = [];

    /**
     * Constructor
     *
     * @param Logger $logger             Logger
     * @param array  $config             Main configuration
     * @param array  $dataSourceSettings Data source settings
     */
    public function __construct(Logger $logger, $config, $dataSourceSettings)
    {
        $this->logger = $logger;
        $this->config = $config;
        $this->dataSourceSettings = $dataSourceSettings;
    }

    /**
     * Set record data
     *
     * @param string $source Source ID
     * @param string $oaiID  Record ID received from OAI-PMH (or empty string for
     * file import)
     * @param string $data   Metadata
     *
     * @return void
     */
    public function setData($source, $oaiID, $data)
    {
        $this->source = $source;
        $this->idPrefix
            = isset($this->dataSourceSettings[$source]['idPrefix'])
            ? $this->dataSourceSettings[$source]['idPrefix']
            : $source;
    }

    /**
     * Return record ID (unique in the data source)
     *
     * @return string
     */
    public function getID()
    {
        die('unimplemented');
    }

    /**
     * Return record linking ID (typically same as ID) used for links
     * between records in the data source
     *
     * @return string
     */
    public function getLinkingID()
    {
        return $this->getID();
    }

    /**
     * Serialize the record for storing in the database
     *
     * @return string
     */
    public function serialize()
    {
        die('unimplemented');
    }

    /**
     * Serialize the record into XML for export
     *
     * @return string
     */
    public function toXML()
    {
        die('unimplemented');
    }

    /**
     * Normalize the record (optional)
     *
     * @return void
     */
    public function normalize()
    {
    }

    /**
     * Return whether the record is a component part
     *
     * @return boolean
     */
    public function getIsComponentPart()
    {
        return false;
    }

    /**
     * Return host record IDs for a component part
     *
     * @return array
     */
    public function getHostRecordIDs()
    {
        return [];
    }

    /**
     * Return fields to be indexed in Solr (an alternative to an XSL transformation)
     *
     * @return array
     */
    public function toSolrArray()
    {
        return [];
    }

    /**
     * Merge component parts to this record
     *
     * @param MongoCollection $componentParts Component parts to be merged
     *
     * @return void
     */
    public function mergeComponentParts($componentParts)
    {
    }

    /**
     * Return record title
     *
     * @param bool $forFiling Whether the title is to be used in filing
     * (e.g. sorting, non-filing characters should be removed)
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getTitle($forFiling = false)
    {
        return '';
    }

    /**
     * Return format from predefined values
     *
     * @return string
     */
    public function getFormat()
    {
        return '';
    }

    /**
     * Component parts: get the volume that contains this component part
     *
     * @return string
     */
    public function getVolume()
    {
        return '';
    }

    /**
     * Component parts: get the issue that contains this component part
     *
     * @return string
     */
    public function getIssue()
    {
        return '';
    }

    /**
     * Component parts: get the start page of this component part in the host record
     *
     * @return string
     */
    public function getStartPage()
    {
        return '';
    }

    /**
     * Component parts: get the container title
     *
     * @return string
     */
    public function getContainerTitle()
    {
        return '';
    }

    /**
     * Component parts: get the reference to the part in the container
     *
     * @return string
     */
    public function getContainerReference()
    {
        return '';
    }

    /**
     * Dedup: Return full title (for debugging purposes only)
     *
     * @return string
     */
    public function getFullTitle()
    {
        return '';
    }

    /**
     * Dedup: Return main author (format: Last, First)
     *
     * @return string
     */
    public function getMainAuthor()
    {
        return '';
    }

    /**
     * Dedup: Return unique IDs (control numbers)
     *
     * @return array
     */
    public function getUniqueIDs()
    {
        return [];
    }

    /**
     * Dedup: Return (unique) ISBNs in ISBN-13 format without dashes
     *
     * @return array
     */
    public function getISBNs()
    {
        return [];
    }

    /**
    * Dedup: Return ISSNs
    *
    * @return array
    */
    public function getISSNs()
    {
        return [];
    }

    /**
     * Dedup: Return series ISSN
     *
     * @return string
     */
    public function getSeriesISSN()
    {
        return '';
    }

    /**
     * Dedup: Return series numbering
     *
     * @return string
     */
    public function getSeriesNumbering()
    {
        return '';
    }

    /**
     * Dedup: Return publication year (four digits only)
     *
     * @return string
     */
    public function getPublicationYear()
    {
        return '';
    }

    /**
     * Dedup: Return page count (number only)
     *
     * @return string
     */
    public function getPageCount()
    {
        return '';
    }

    /**
     * Dedup: Add the dedup key to a suitable field in the metadata.
     * Used when exporting records to a file.
     *
     * @param string $dedupKey Dedup key to be added
     *
     * @return void
     */
    public function addDedupKeyToMetadata($dedupKey)
    {
    }

    /**
     * Check if record has access restrictions.
     *
     * @return string 'restricted' or more specific licence id if restricted,
     * empty string otherwise
     */
    public function getAccessRestrictions()
    {
        return '';
    }

    /**
     * Get any warnings about problems processing the record.
     *
     * @return array
     */
    public function getProcessingWarnings()
    {
        return array_unique($this->warnings);
    }

    /**
     * Return a parameter specified in driverParams[] of datasources.ini
     *
     * @param string $parameter Parameter name
     * @param bool   $default   Default value if the parameter is not set
     *
     * @return mixed Value
     */
    protected function getDriverParam($parameter, $default = true)
    {
        if (!isset($this->dataSourceSettings[$this->source]['driverParams'])
        ) {
            return $default;
        }
        $iniValues = parse_ini_string(
            implode(
                PHP_EOL,
                $this->dataSourceSettings[$this->source]['driverParams']
            )
        );

        return isset($iniValues[$parameter]) ? $iniValues[$parameter] : $default;
    }

    /**
     * Store a warning message about problems with the record
     *
     * @param string $msg Message
     *
     * @return void
     */
    protected function storeWarning($msg)
    {
        $this->warnings[] = $msg;
    }

    /**
     * Verify that a string is valid ISO8601 date
     *
     * @param string $dateString Date string
     *
     * @return string Valid date string or an empty string if invalid
     */
    protected function validateDate($dateString)
    {
        if (MetadataUtils::validateISO8601Date($dateString) !== false) {
            return $dateString;
        }
        return '';
    }
}
