<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * File for widget functionality
 * WPInventory supports template overrides.
 * @author WP Inventory Manager
 *
 */
class WPInventory_Categories_Widget extends WP_Widget {
	function WPInventory_Categories_Widget() {
		parent::__construct( 'WPInventory_Categories_Widget', 'WP Inventory Categories', array( 'description' => 'List Inventory categories, with link(s) to view inventory for each category' ) );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$page_id = ( ! empty( $instance['page_id'] ) ) ? $instance['page_id'] : NULL;

		if ( ! $page_id ) {
			echo '<!-- Page not set in widget.  Defaulting to current page / post -->';
			global $post;
			if ( $post ) {
				$page_id = $post->ID;
			} else {
				return;
			}
		}

		echo $before_widget;
		if ( $instance['title'] ) {
			echo $before_title . $instance['title'] . $after_title;
		}

		$wpim_categories = new WPIMCategory();
		$categories      = $wpim_categories->get_all( array( 'order' => $instance['sort_order'] ) );

		$list = ( $instance['display_as'] != 'list' ) ? FALSE : TRUE;

		echo ( $list ) ? '<ol>' : '<select name="inventory_category_list" onchange="if (this.value) window.location.href=this.value"><option value="">' . WPIMCore::__( 'Choose Category...' ) . '</option>';

		foreach ( $categories AS $category ) {
			$category_link = $wpim_categories->get_category_permalink( $page_id, $category->category_id, $category->category_name );
			if ( $list ) {
				echo '<li class="category_' . $category->category_id . ' category_' . $wpim_categories->get_class( $category->category_name ) . '">';
				echo '<a href="' . $category_link . '">' . $category->category_name . '</a>';
				echo '</li>';
			} else {
				echo '<option value="' . $category_link . '">' . $category->category_name . '</option>';
			}
		}

		echo ( $list ) ? '</ol>' : '</select>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $new_instance as $k => $v ) {
			$instance[ $k ] = $v;
		}

		return $instance;
	}

	function form( $instance ) {
		$default  = array(
			'title'          => WPIMCore::__( 'Inventory Categories' ),
			'page_id'        => '',
			'sort_order'     => '',
			'display_as'     => 'list',
			'include_counts' => '0'
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$display_as_select = WPIMCore::dropdown_array( $this->get_field_name( 'display_as' ), $instance['display_as'], array( 'list'   => WPIMCore::__( 'List' ),
		                                                                                                                      'select' => WPIMCore::__( 'Dropdown' )
		) );
		$sort_order_select = WPIMCore::dropdown_array( $this->get_field_name( 'sort_order' ), $instance['sort_order'], array( 'sort_order'    => WPIMCore::__( 'Sort Order' ),
		                                                                                                                      'category_name' => WPIMCore::__( 'Category Name' )
		) );

		echo '<p><label for="' . $this->get_field_name( 'title' ) . '">' . WPIMCore::__( 'Widget Title' ) . '</label> <input type="text" class="widefat" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" /></p>';
		echo '<p><label for="' . $this->get_field_name( 'page_id' ) . '">' . WPIMCore::__( 'Links to Page' ) . '</label> ' . wp_dropdown_pages( 'echo=0&name=' . $this->get_field_name( 'page_id' ) . '&selected=' . $instance['page_id'] . '&show_option_none=' . WPIMCore::__( 'Select...' ) ) . '</p>';
		echo '<p><label for="' . $this->get_field_name( 'display_as' ) . '">' . WPIMCore::__( 'Display As' ) . '</label> ' . $display_as_select . '</p>';
		echo '<p><label for="' . $this->get_field_name( 'sort_order' ) . '">' . WPIMCore::__( 'Sort Order' ) . '</label> ' . $sort_order_select . '</p>';
	}
}


class WPInventory_Latest_Items_Widget extends WP_Widget {
	function WPInventory_Latest_Items_Widget() {
		parent::__construct( 'WPInventory_Latest_Items_Widget', 'WP Inventory Latest Items', array( 'description' => 'List the latest items added to inventory.' ) );
	}

	function widget( $args, $instance ) {

		extract( $args );
		$page_id = ( ! empty( $instance['page_id'] ) ) ? $instance['page_id'] : NULL;

		if ( ! $page_id ) {
			echo '<!-- Page not set in widget.  Defaulting to current page / post -->';
			global $post;
			if ( $post ) {
				$page_id = $post->ID;
			} else {
				return;
			}
		}

		echo $before_widget;
		if ( $instance['title'] ) {
			echo $before_title . $instance['title'] . $after_title;
		}

		$number = (int) $instance['number'];
		$number = max( 1, min( 10, $number ) );

		$args = array(
			'category_id' => $instance['category_id'],
			'page_size'   => $number,
			'order'       => 'inventory_date_added'
		);

		$custom_loop = new WPIMLoop();
		$custom_loop->set_single( TRUE );
		$custom_loop->load_items( $args );
		wpinventory_set_loop( $custom_loop );

		wpinventory_get_template_part( 'widget-latest-items-loop' );

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		foreach ( $new_instance as $k => $v ) {
			$instance[ $k ] = $v;
		}

		return $instance;
	}

	function form( $instance ) {
		$default  = array(
			'title'       => WPIMCore::__( 'Latest Items' ),
			'page_id'     => '',
			'category_id' => '',
			'number'      => '4'
		);
		$instance = wp_parse_args( (array) $instance, $default );

		$WPIMCategories = new WPIMCategory();
		$categories     = $WPIMCategories->get_all( array( 'order' => 'sort_order' ) );

		$categories_array = array( '' => WPIMCore::__( 'Show All' ) );
		foreach ( $categories AS $cat ) {
			$categories_array[ $cat->category_id ] = $cat->category_name;
		}

		$category_select = WPIMCore::dropdown_array( $this->get_field_name( 'category_id' ), $instance['category_id'], $categories_array );

		echo '<p><label for="' . $this->get_field_name( 'title' ) . '">' . WPIMCore::__( 'Widget Title' ) . '</label> <input type="text" class="widefat" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" /></p>';
		echo '<p><label for="' . $this->get_field_name( 'number' ) . '">' . WPIMCore::__( 'Number of Items' ) . '</label> <input type="text" class="small-text" name="' . $this->get_field_name( 'number' ) . '" value="' . $instance['number'] . '" /></p>';
		echo '<p><label for="' . $this->get_field_name( 'page_id' ) . '">' . WPIMCore::__( 'Links to Page' ) . '</label> ' . wp_dropdown_pages( 'echo=0&name=' . $this->get_field_name( 'page_id' ) . '&selected=' . $instance['page_id'] . '&show_option_none=' . WPIMCore::__( 'Select...' ) ) . '</p>';
		echo '<p><label for="' . $this->get_field_name( 'category_id' ) . '">' . WPIMCore::__( 'Category' ) . '</label> ' . $category_select . '</p>';
	}
}