<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border as Group_Control_Border;
use Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BetterDocs_Elementor_Sidebar extends Widget_Base
{
    
    public function get_name()
    {
        return 'betterdocs-sidebar';
    }
    
    public function get_title()
    {
        return __( 'Doc Sidebar', 'betterdocs' );
    }
    
    public function get_icon()
    {
        return 'betterdocs-icon-Sidebar';
    }
    
    public function get_categories()
    {
        return ['betterdocs-elements'];
    }
    
    public function get_keywords()
    {
        return ['betterdocs-elements', 'sidebar', 'betterdocs', 'docs'];
    }
    
    public function get_custom_help_url()
    {
        return 'https://betterdocs.co/docs/single-doc-in-elementor';
    }
    
    protected function _register_controls()
    {
        
        $this->box_setting_style();
        
        $this->icon_style();
        
        $this->title_style();
        
        $this->count_style();
        
        $this->list_setting();
        
        $this->sub_list_setting();
        
        
    }
    
    public function box_setting_style()
    {
        /**
         * ----------------------------------------------------------
         * Section: Box Styles
         * ----------------------------------------------------------
         */
        $this->start_controls_section(
            'section_card_settings',
            [
                'label' => __( 'Box', 'betterdocs' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'column_space', // Legacy control id but new control
            [
                'label'      => __( 'Box Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->add_responsive_control(
            'column_padding',
            [
                'label'      => __( 'Box Padding', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'box_seperator_color',
            [
                'label'     => esc_html__( 'Separator Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .betterdocs-categories-wrap' => 'background-color: {{VALUE}};'
                ],
            ]
        );
        
        $this->start_controls_tabs( 'card_settings_tabs' );
        
        // Normal State Tab
        $this->start_controls_tab(
            'card_normal',
            ['label' => esc_html__( 'Normal', 'betterdocs' )]
        );
        
        $this->add_control(
            'box_section_header',
            [
                'label'     => __( 'Header', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg_normal_header',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-title-wrap'
            ]
        );
        
        $this->add_control(
            'box_section_body',
            [
                'label'     => __( 'Body', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg_normal',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-item-container'
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border_normal',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap'
            ]
        );
        
        $this->add_responsive_control(
            'card_border_radius_normal',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow_normal',
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap'
            ]
        );
        
        $this->end_controls_tab();
        
        // Hover State Tab
        $this->start_controls_tab(
            'card_hover',
            ['label' => esc_html__( 'Hover', 'betterdocs' )]
        );
        
        $this->add_control(
            'card_transition',
            [
                'label'      => __( 'Transition', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 300,
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range'      => [
                    '%' => [
                        'max'  => 2500,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );
        
        $this->add_control(
            'box_section_header_hover',
            [
                'label'     => __( 'Header', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg_hover_header',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover .docs-cat-title-wrap'
            ]
        );
        
        $this->add_control(
            'box_section_body_hover',
            [
                'label'     => __( 'Body', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'card_bg_hover',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover .docs-item-container'
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border_hover',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover'
            ]
        );
        
        $this->add_responsive_control(
            'card_border_radius_hover',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow_hover',
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover'
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        $this->end_controls_section(); # end of 'Card Settings'
    }
    
    public function icon_style()
    {
        /**
         * ----------------------------------------------------------
         * Section: Icon Styles
         * ----------------------------------------------------------
         */
        $this->start_controls_section(
            'section_box_icon_style',
            [
                'label' => __( 'Icon', 'betterdocs' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'category_settings_area',
            [
                'label' => __( 'Area', 'betterdocs' ),
                'type'  => Controls_Manager::HEADING
            ]
        );
        
        $this->add_responsive_control(
            'category_settings_icon_area_size_normal',
            [
                'label'      => esc_html__( 'Size', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'category_settings_icon',
            [
                'label'     => __( 'Icon', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->start_controls_tabs( 'box_icon_styles_tab' );
        
        // Normal State Tab
        $this->start_controls_tab(
            'icon_normal',
            ['label' => esc_html__( 'Normal', 'betterdocs' )]
        );
        
        $this->add_responsive_control(
            'category_settings_icon_size_normal',
            [
                'label'      => esc_html__( 'Size', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icon_background_normal',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon',
                'exclude'  => [
                    'image'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'icon_border_normal',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon'
            ]
        );
        
        $this->add_responsive_control(
            'icon_border_radius_normal',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_spacing',
            [
                'label'              => esc_html__( 'Spacing', 'betterdocs' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', 'em', '%'],
                'allowed_dimensions' => [
                    'top',
                    'bottom'
                ],
                'selectors'          => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon' => 'margin: {{TOP}}{{UNIT}} auto {{BOTTOM}}{{UNIT}} auto;'
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        // Hover State Tab
        $this->start_controls_tab(
            'icon_hover',
            ['label' => esc_html__( 'Hover', 'betterdocs' )]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icon_background_hover',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon:hover'
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'icon_border_hover',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon:hover'
            ]
        );
        
        $this->add_responsive_control(
            'icon_border_radius_hover',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon:hover:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            
            ]
        );
        
        $this->add_control(
            'category_settings_icon_size_transition',
            [
                'label'      => __( 'Transition', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 300,
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range'      => [
                    '%' => [
                        'max'  => 2500,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-icon:hover' => 'transition: {{SIZE}}ms;'
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section(); # end of 'Icon Styles'
    }
    
    
    public function title_style()
    {
        /**
         * ----------------------------------------------------------
         * Section: Title Styles
         * ----------------------------------------------------------
         */
        $this->start_controls_section(
            'section_box_title_styles',
            [
                'label' => __( 'Title', 'betterdocs' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs( 'box_title_styles_tab' );
        
        // Normal State Tab
        $this->start_controls_tab(
            'title_normal',
            ['label' => esc_html__( 'Normal', 'betterdocs' )]
        );
        
        $this->add_control(
            'cat_title_color_normal',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-title h3' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'cat_title_typography_normal',
                'selector' => '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-title h3'
            ]
        );
        
        $this->add_responsive_control(
            'title_spacing',
            [
                'label'      => __( 'Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-title h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        // Hover State Tab
        $this->start_controls_tab(
            'title_hover',
            ['label' => esc_html__( 'Hover', 'betterdocs' )]
        );
        
        $this->add_control(
            'cat_title_color_hover',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap:hover .docs-cat-title h3' => 'color: {{VALUE}};'
                ],
            ]
        );
        
        $this->add_control(
            'category_title_transition',
            [
                'label'      => __( 'Transition', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 300,
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range'      => [
                    '%' => [
                        'max'  => 2500,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .betterdocs-categories-wrap .docs-single-cat-wrap .docs-cat-title h3' => 'transition: {{SIZE}}ms;',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section(); # end of 'Icon Styles'
    }
    
    public function count_style()
    {
        /**
         * ----------------------------------------------------------
         * Section: Count Styles
         * ----------------------------------------------------------
         */
        $this->start_controls_section(
            'section_box_count_styles',
            [
                'label' => __( 'Count', 'betterdocs' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->add_control(
            'count_styles_heading',
            [
                'label'     => __( 'Count', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        
        $this->start_controls_tabs( 'box_count_styles_tab' );
        
        // Normal State Tab
        $this->start_controls_tab(
            'count_normal',
            ['label' => esc_html__( 'Normal', 'betterdocs' )]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'count_typography_normal',
                'selector' => '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count span'
            ]
        );
        
        $this->add_control(
            'count_color_normal',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count span' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'count_box_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'count_box_border',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count',
            ]
        );
        
        $this->add_responsive_control(
            'count_box_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'count_box_box_shadow',
                'selector' => '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count',
            ]
        );
        
        $this->add_responsive_control(
            'count_box_size',
            [
                'label'      => esc_html__( 'Size', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'count_spacing',
            [
                'label'      => __( 'Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        // Hover State Tab
        $this->start_controls_tab(
            'count_hover',
            ['label' => esc_html__( 'Hover', 'betterdocs' )]
        );
        
        $this->add_control(
            'count_color_hover',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count:hover span' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'count_box_bg_hover',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count:hover',
            
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'count_box_border_hover',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count:hover',
            
            ]
        );
        
        $this->add_responsive_control(
            'count_box_border_radius_hover',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'count_box_box_shadow_hover',
                'selector' => '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count:hover',
            ]
        );
        
        $this->add_control(
            'category_count_transition',
            [
                'label'      => __( 'Transition', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 300,
                    'unit' => '%',
                ],
                'size_units' => ['%'],
                'range'      => [
                    '%' => [
                        'max'  => 2500,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .docs-single-cat-wrap .docs-cat-title-wrap .docs-item-count:hover' => 'transition: {{SIZE}}ms;',
                
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section(); # end of 'Count Styles'
    }
    
    public function list_setting()
    {
        /**
         * ----------------------------------------------------------
         * Section: List Settinggs
         * ----------------------------------------------------------
         */
        $this->start_controls_section(
            'section_article_settings',
            [
                'label' => __( 'Category List', 'betterdocs' ),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'list_item_typography',
                'selector' => '{{WRAPPER}} .docs-item-container ul li a',
            ]
        );
        
        $this->add_control(
            'list_color',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-item-container ul li a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'list_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-item-container ul li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'list_margin',
            [
                'label'      => esc_html__( 'List Item Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-item-container ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_responsive_control(
            'list_area_padding',
            [
                'label'              => esc_html__( 'List Area Padding', 'betterdocs' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => 'vertical',
                'size_units'         => ['px', 'em', '%'],
                'selectors'          => [
                    '{{WRAPPER}} .docs-item-container' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'icon_settings_heading',
            [
                'label'     => esc_html__( 'Icon', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        
        $this->add_control(
            'list_icon_color',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-item-container li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'list_icon_size',
            [
                'label'      => __( 'Size', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    '%' => [
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .docs-item-container li svg' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'list_icon_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-item-container li svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section(); # end of 'Column Settings'
        
    }
    
    /**
     * ----------------------------------------------------------
     * Section: Sub List Settinggs
     * ----------------------------------------------------------
     */
    public function sub_list_setting()
    {
        
        $this->start_controls_section(
            'section_sub_list_settings',
            [
                'label' => __( 'Sub-Category List', 'betterdocs' ),
                'tab'   => Controls_Manager::TAB_STYLE
            ]
        );
        
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_list_item_typography',
                'selector' => '{{WRAPPER}} .docs-item-container .docs-sub-cat-title a',
            ]
        );
        
        $this->add_control(
            'sub_list_color',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-item-container .docs-sub-cat-title a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'sub_list_hover_color',
            [
                'label'     => esc_html__( 'Hover Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-item-container .docs-sub-cat-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'sub_list_margin',
            [
                'label'      => esc_html__( 'List Item Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-item-container .docs-sub-cat-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        
        $this->add_responsive_control(
            'sub_list_area_padding',
            [
                'label'              => esc_html__( 'List Area Padding', 'betterdocs' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => 'vertical',
                'size_units'         => ['px', 'em', '%'],
                'selectors'          => [
                    '{{WRAPPER}} .docs-item-container .docs-sub-cat-title' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'sub_list_icon_settings_heading',
            [
                'label'     => esc_html__( 'Icon', 'betterdocs' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        
        $this->add_control(
            'sub_list_icon_color',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-item-container .docs-sub-cat-title svg' => 'fill: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'sub_list_icon_size',
            [
                'label'      => __( 'Size', 'betterdocs' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    '%' => [
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .docs-item-container .docs-sub-cat-title svg' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'sub_list_icon_spacing',
            [
                'label'      => esc_html__( 'Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-item-container .docs-sub-cat-title svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section(); # end of 'Column Settings'
    }
    
    public function button_setting()
    {
        /**
         * ----------------------------------------------------------
         * Section: Button Settings
         * ----------------------------------------------------------
         */
        $this->start_controls_section(
            'section_button_settings',
            [
                'label' => __( 'Button', 'betterdocs' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
        $this->start_controls_tabs(
            'button_settings_tabs'
        );
        
        // Normal State Tab
        $this->start_controls_tab(
            'button_normal',
            ['label' => esc_html__( 'Normal', 'betterdocs' )]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography_normal',
                'selector' => '{{WRAPPER}} .docs-cat-link-btn',
            ]
        );
        
        $this->add_control(
            'button_color_normal',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-cat-link-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_background_normal',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .docs-cat-link-btn',
                'exclude'  => [
                    'image',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_normal',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .docs-cat-link-btn',
            ]
        );
        
        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-cat-link-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__( 'Padding', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-cat-link-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_area_margin',
            [
                'label'      => esc_html__( 'Area Spacing', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-cat-link-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        // Normal State Tab
        $this->start_controls_tab(
            'button_hover',
            ['label' => esc_html__( 'Hover', 'betterdocs' )]
        );
        
        $this->add_control(
            'button_color_hover',
            [
                'label'     => esc_html__( 'Color', 'betterdocs' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .docs-cat-link-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'button_background_hover',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .docs-cat-link-btn:hover',
                'exclude'  => [
                    'image',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_hover',
                'label'    => esc_html__( 'Border', 'betterdocs' ),
                'selector' => '{{WRAPPER}} .docs-cat-link-btn:hover',
            ]
        );
        
        $this->add_responsive_control(
            'button_hover_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'betterdocs' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .docs-cat-link-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
            'button_text_alignment',
            [
                'label'     => __( 'Text Alignment', 'betterdocs' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'betterdocs' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'betterdocs' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'betterdocs' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .docs-cat-link-btn' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'button_alignment',
            [
                'label'     => __( 'Button Alignment', 'betterdocs' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'betterdocs' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'betterdocs' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'betterdocs' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .docs-item-container .docs-cat-link-btn' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section(); # end of 'Button Settings'
    }
    
    protected function render()
    {
        $shortcode = do_shortcode( '[betterdocs_category_grid sidebar_list="true" posts_per_grid="-1"]' );
        $shortcode = apply_filters( 'betterdocs_sidebar_category_shortcode', $shortcode );
        printf( '<aside id="betterdocs-sidebar" class="betterdocs-el-single-sidebar"><div class="betterdocs-sidebar-content">%s</div>%s</aside>',
            $shortcode, $this->get_toc() );
    }
    
    public function get_toc()
    {
        ob_start();
        $enable_toc = BetterDocs_DB::get_settings( 'enable_toc' );
        $enable_sticky_toc = BetterDocs_DB::get_settings( 'enable_sticky_toc' );
        if ( $enable_toc == 1 && $enable_sticky_toc == 1 ) {
            ?>
            <div id="st-test" class="sticky-toc-container">
                <a class="close-toc" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16px" viewBox="0 0 24 24">
                        <path style="line-height:normal;text-indent:0;text-align:start;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000;text-transform:none;block-progression:tb;isolation:auto;mix-blend-mode:normal"
                              d="M 4.9902344 3.9902344 A 1.0001 1.0001 0 0 0 4.2929688 5.7070312 L 10.585938 12 L 4.2929688 18.292969 A 1.0001 1.0001 0 1 0 5.7070312 19.707031 L 12 13.414062 L 18.292969 19.707031 A 1.0001 1.0001 0 1 0 19.707031 18.292969 L 13.414062 12 L 19.707031 5.7070312 A 1.0001 1.0001 0 0 0 18.980469 3.9902344 A 1.0001 1.0001 0 0 0 18.292969 4.2929688 L 12 10.585938 L 5.7070312 4.2929688 A 1.0001 1.0001 0 0 0 4.9902344 3.9902344 z"
                              font-weight="400" font-family="sans-serif" white-space="normal" overflow="visible"></path>
                    </svg>
                </a>
            </div><!-- #sticky toc -->
            <?php
        }
        return ob_get_clean();
    }
}
