<?php
/*
 * tinyMongo.class.php
 *
 * Copyright 2012 derRaphael <software@itholic.org>
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are
 * met:
 *
 * * Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above
 *   copyright notice, this list of conditions and the following disclaimer
 *   in the documentation and/or other materials provided with the
 *   distribution.
 * * Neither the name of the  nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace tinyTpl\db
{
    /**
     * tinyMongo
     *
     * Interface for accessing the Database. This class includes a few
     * basic methods for working with mongodb
     *
    **/

    class tinyMongo
    {

        public $connection;
        public $collection;
        public $db;

        /*
         *
         * name: __construct
         * @param $options
         * @return
         *
         */
        public function __construct( $options = array() )
        {
            $defaults = array(
                "host"              => "localhost:27017",
                "database"          => null,
                "connectionOptions" => array( 'persistent' => "tiny" )
            );

            foreach( array_merge( $defaults, $options ) as $key => $value )
            {
                ${$key} = $value;
            }

            $this->connection = new \Mongo( $host, $connectionOptions );
            $this->init( $database );
        }

        /*
         *
         * name: init
         * @param $database
         * @return
         *
         */
        public function init( $database = null )
        {
            if ( $database == null )
            {
                $database = "tinyTpl";

            } else if ( ! is_string( $database ) ) {

                throw new \Exception( "Is no valid MongoDB name. Was Expecting String.", 100 );
            }

            return $this->setDatabase( $database );
        }

        /*
         *
         * name: setDatabase
         * @param $database
         * @return
         *
         */
        public function setDatabase( $database )
        {
            $this->db = $this->connection->selectDB( $database );
            return $this->db;
        }

        /*
         *
         * name: setCollection
         * @param $collection
         * @return
         *
         */
        public function setCollection( $collection )
        {
            $this->collection = $this->db->selectCollection( $collection );
            return $this->collection;
        }

        /*
         *
         * name: insert
         * @param $dataset
         * @return
         *
         */
        public function insert($dataset, $options = array() )
        {
            return $this->collection->insert($dataset, $options);
        }

        /*
         *
         * name: save
         * @param $dataset
         * @return
         *
         */
        public function save($dataset, $options = array() )
        {
            return $this->collection->save($dataset,$options);
        }

        /*
         *
         * name: find
         * @param $citeria
         * @return MongoCursor
         *
         */
        public function find( $query = array(), $fields = array() )
        {
            return $this->collection->find( $query, $fields );
        }

        /*
         *
         * name: findAll
         * @param $citeria
         * @return array
         *
         */
        public function findAll( $query = array(), $fields = array() )
        {
            $cursor = $this->find( $query, $fields );

            $result = array();
            $index  = 0;

            while( $cursor->hasNext())
            {
                $result[$index] = $cursor->getNext();
                $index++;
            }

            return $result;
        }

        /*
         *
         * name: findById
         * @param $id
         * @return
         *
         */
        public function findById( $id )
        {
            if ( ! is_string( $id ) )
            {
                $id = new \MongoId( $id );
            }

            $criteria = array( '_id' => $id );

            return $this->findOne( $criteria );
        }

        /*
         *
         * name: findOne
         * @param $citeria
         * @return
         *
         */
        public function findOne( $query=array(), $fields=array() )
        {
            return $this->collection->findOne( $query, $fields );
        }

        /*
         *
         * name: update
         * @param $criteria
         * @param $values
         * @param $options
         * @return
         *
         */
        public function update( $criteria, $new_object, $options=array() )
        {
            return $this->collection->update( $criteria, $new_object, $options );
        }

        /*
         *
         * name: delete
         * @param $citeria
         * @param $one
         * @return $data
         *
         */
        public function delete( $criteria, $one = false )
        {
            $data = $this->collection->remove( $criteria, $one );
            return $data;
        }

        /*
         *
         * name: ensureindex
         * @param $args - Index arguments
         * @return
         *
         */
        public function ensureIndex( $args )
        {
            return $this->collection->ensureIndex( $args );
        }

    }
}
?>