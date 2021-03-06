<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discharge_model extends CRUD_Model {
    public $table = 'discharges';
    public $primary_key = 'discharges.id';
    
    public function validation_rules_add() {
        return array(
            array(
                'field' => 'member_id'
                ,'rules' => 'required|numeric'
            )
            ,array(
                'field' => 'date'
                ,'rules' => 'required'
            )
            ,array(
                'field' => 'type'
                ,'rules' => 'required'
            )
            ,array(
                'field' => 'reason'
                ,'rules' => 'required'
            )
            ,array(
                'field' => 'reason'
                ,'rules' => 'required'
            )
            ,array(
                'field' => 'was_reversed'
                ,'rules' => 'numeric|greater_than[-1]|less_than[2]'
            )
            ,array(
                'field' => 'forum_id'
                ,'rules' => 'numeric'
            )
            ,array(
                'field' => 'topic_id'
                ,'rules' => 'numeric'
            )
        );
    }
    
    public function validation_rules_edit() {
        return array(
            array(
                'field' => 'member_id'
                ,'rules' => 'numeric'
            )
            /*,array(
                'field' => 'date'
                ,'rules' => ''
            )*/
            /*,array(
                'field' => 'type'
                ,'rules' => ''
            )*/
            /*,array(
                'field' => 'reason'
                ,'rules' => ''
            )*/
            /*,array(
                'field' => 'reason'
                ,'rules' => ''
            )*/
            ,array(
                'field' => 'was_reversed'
                ,'rules' => 'numeric|greater_than[-1]|less_than[2]'
            )
            ,array(
                'field' => 'forum_id'
                ,'rules' => 'numeric'
            )
            ,array(
                'field' => 'topic_id'
                ,'rules' => 'numeric'
            )
        );
    }
    
    public function default_select() {
        $this->db->select('SQL_CALC_FOUND_ROWS discharges.*, members.id AS `member|id`', FALSE)
            ->select($this->virtual_fields['short_name'] . ' AS `member|short_name`', FALSE);
    }
    
    public function default_join() {
        $this->db->join('members', 'members.id = discharges.member_id')
            ->join('ranks', 'ranks.id = members.rank_id');
    }
    
    public function default_order_by() {
        $this->db->order_by('discharges.date DESC');
    }
    
    // Won't work, need to go by assignments
    public function by_unit($unit_id) {
        $this->filter_select('events.unit_id AS `unit|id`, units.abbr AS `unit|abbr`, ' . $this->virtual_fields['unit_key'] . ' AS `unit|key`', FALSE);
        $this->filter_join('units', 'units.id = events.unit_id');
        $this->filter_where('(units.id = ' . $unit_id . ' OR units.path LIKE "%/' . $unit_id . '/%")');
        $this->filter_group_by('attendance.event_id');
        $this->filter_order_by('events.datetime DESC');
        return $this;
    }
}