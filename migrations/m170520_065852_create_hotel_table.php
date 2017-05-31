<?php

use yii\db\Migration;

/**
 * Handles the creation of table `hotel`.
 */
class m170520_065852_create_hotel_table extends Migration
{
    private $tableHotel = 'rh_hotel';
    private $tableHotelType = 'rh_hotel_type';
    private $tableRoom = 'rh_room';
    private $tableRoomType = 'rh_room_type';
    private $tableAttributes = 'rh_attributes';
    private $tableAttributesAssignment = 'rh_attributes_assignment';
    private $tablePhotos = 'rh_photos';

    private $hotelTypes = [
        'hotel' => 'Hotel',
        'hostel' => 'Hostel',
        'motel' => 'Motel',
        'cottage' => 'Cottage',
        'chalet' => 'Chalet',
        'resort' => 'Resort',
        'villa' => 'Villa',
        'apartment' => 'Apartment',
        'camp' => 'Camping',
        'bb' => 'Bed and Breakfast',
        'penthouse' => 'Penthouse',
        'guesthouse' => 'Guesthouse',
        'pension' => 'Pension',
        'townhouse' => 'Townhouse',
    ];

    private $roomTypes = [
        'std' => 'Standart',
        'mod' => 'Moderate',
        'sgl' => 'Single',
        'dbl' => 'Double',
        'trpl' => 'Triple',
        'q' => 'Queen',
        't' => 'Twin',
        'dlx' => 'Deluxe',
        'dpl' => 'Duplex',
        'fml' => 'Family',
        'k' => 'King',
        'ck' => 'California king',
        'stu' => 'Studio',
        'ste' => 'Suite',
        'jrste' => 'Junior suite',
        'sup' => 'Superior',
        'roh' => 'Run of House',
    ];

    /**
     * @inheritdoc
     */
    public function createTable($tableName, $columns, $options = null)
    {
        if (!in_array($tableName, Yii::$app->db->schema->tableNames)) {
            parent::createTable($tableName, $columns, $options);
        }
    }

    /**
     * @inheritdoc
     */
    public function dropTable($tableName)
    {
        if (in_array($tableName, Yii::$app->db->schema->tableNames)) {
            parent::dropTable($tableName);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableHotel, [
            'hid'         => $this->primaryKey(),
            'user_id'     => $this->integer()->defaultValue(0),
            'name'        => $this->string()->notNull(),
            'description' => $this->text(),
            'stars'       => $this->smallInteger()->notNull()->defaultValue(0),
            'country'     => $this->string(),
            'region'      => $this->string(),
            'city'        => $this->string(),
            'address'     => $this->string(),
            'map_lat'     => $this->decimal(12, 9),
            'map_lng'     => $this->decimal(12, 9),
            'hotel_type'  => $this->string(100),
            'check_in'    => $this->string(10)->notNull(),
            'check_out'   => $this->string(10)->notNull(),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer(),
        ], $tableOptions);

        $this->createTable($this->tableHotelType, [
            'name'        => $this->string(100)->notNull(),
            'description' => $this->string()->notNull(),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer(),
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($this->tableRoom, [
            'rid'         => $this->primaryKey(),
            'hotel_id'    => $this->integer()->notNull(),
            'name'        => $this->string()->notNull(),
            'description' => $this->text(),
            'floor'       => $this->integer()->notNull(),
            'room_number' => $this->integer()->notNull(),
            'square'      => $this->string(64)->notNull(),
            'price'       => $this->decimal(10, 2)->notNull(),
            'room_type'   => $this->string(100),
            'beds'        => $this->integer()->notNull(),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer(),
        ], $tableOptions);

        $this->createTable($this->tableRoomType, [
            'name'        => $this->string(100)->notNull(),
            'description' => $this->string()->notNull(),
            'created_at'  => $this->integer(),
            'updated_at'  => $this->integer(),
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($this->tableAttributes, [
            'aid'        => $this->primaryKey(),
            'type'       => $this->smallInteger()->notNull(),
            'name'       => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createTable($this->tableAttributesAssignment, [
            'attribute_id' => $this->integer(),
            'hotel_id'     => $this->integer(),
            'room_id'      => $this->integer(),
            'created_at'   => $this->integer(),
            'PRIMARY KEY (attribute_id, hotel_id, room_id)',
            'CONSTRAINT UNIQUE (attribute_id, hotel_id, room_id)',
            'CONSTRAINT CHECK (hotel_id IS NOT NULL OR room_id IS NOT NULL)',
        ], $tableOptions);

        $this->createTable($this->tablePhotos, [
            'pid'        => $this->integer(),
            'hotel_id'   => $this->integer(),
            'room_id'    => $this->integer(),
            'uri'        => $this->string(),
            'main'       => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (pid, hotel_id, room_id)',
            'CONSTRAINT UNIQUE (pid, hotel_id, room_id)',
            'CONSTRAINT CHECK (hotel_id IS NOT NULL OR room_id IS NOT NULL)',
        ], $tableOptions);

        $this->createIndex('idx-hotel-user_id', $this->tableHotel, 'user_id');
        $this->createIndex('idx-room-hotel_id', $this->tableRoom, 'hotel_id');

        $this->addForeignKey('fk_hotel_user_id', $this->tableHotel, 'user_id', 'user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_hotel_hotel_type', $this->tableHotel, 'hotel_type', $this->tableHotelType, 'name', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_room_room_type', $this->tableRoom, 'room_type', $this->tableRoomType, 'name', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_room_hotel_id', $this->tableRoom, 'hotel_id', $this->tableHotel, 'hid', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk_aa_attribute_id', $this->tableAttributesAssignment, 'attribute_id', $this->tableAttributes, 'aid', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_aa_hotel_id', $this->tableAttributesAssignment, 'hotel_id', $this->tableHotel, 'hid', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_aa_room_id', $this->tableAttributesAssignment, 'room_id', $this->tableRoom, 'rid', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_photos_hotel_id', $this->tablePhotos, 'hotel_id', $this->tableHotel, 'hid', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_photos_room_id', $this->tablePhotos, 'room_id', $this->tableRoom, 'rid', 'CASCADE', 'CASCADE');

        // insert hotel types
        $hotelTypeInsert = [];
        $time = time();
        foreach ($this->hotelTypes as $name => $description) {
            $hotelTypeInsert[] = [$name, $description, $time, $time];
            $time++;
        }
        Yii::$app->db->createCommand()->batchInsert(
            $this->tableHotelType,
            ['name', 'description', 'created_at', 'updated_at'],
            $hotelTypeInsert)
        ->execute();

        // insert room types
        $roomTypeInsert = [];
        $time = time();
        foreach ($this->roomTypes as $name => $description) {
            $roomTypeInsert[] = [$name, $description, $time, $time];
            $time++;
        }
        Yii::$app->db->createCommand()->batchInsert(
            $this->tableRoomType,
            ['name', 'description', 'created_at', 'updated_at'],
            $roomTypeInsert)
        ->execute();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropForeignKey('fk_photos_room_id', $this->tablePhotos);
        $this->dropForeignKey('fk_photos_hotel_id', $this->tablePhotos);
        $this->dropForeignKey('fk_aa_room_id', $this->tableAttributesAssignment);
        $this->dropForeignKey('fk_aa_hotel_id', $this->tableAttributesAssignment);
        $this->dropForeignKey('fk_aa_attribute_id', $this->tableAttributesAssignment);
        $this->dropForeignKey('fk_room_hotel_id', $this->tableRoom);
        $this->dropForeignKey('fk_room_room_type', $this->tableRoom);
        $this->dropForeignKey('fk_hotel_hotel_type', $this->tableHotel);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

        // $this->dropIndex('idx-room-hotel_id', $this->tableRoom);
        // $this->dropIndex('idx-hotel-user_id', $this->tableHotel);

        $this->dropTable($this->tablePhotos);
        $this->dropTable($this->tableAttributesAssignment);
        $this->dropTable($this->tableAttributes);
        $this->dropTable($this->tableRoomType);
        $this->dropTable($this->tableRoom);
        $this->dropTable($this->tableHotelType);
        $this->dropTable($this->tableHotel);
    }
}
