<?php
namespace Elementor;

class Demo_Widget extends Widget_Base {

    public function get_name() {
        return 'demo';
    }

    public function get_title() {
        return __( 'demo', 'addons-kit' );
    }

    public function get_icon() {
        return 'fa fa-code';
    }

    public function get_style_depends() {
        return ['demo-css'];
    }

    public function get_script_depends() {
        return ['demo-js'];
    }

    public function get_categories() {
        return ['addons-kit'];
    }

    protected function _register_controls() {
        /**
         * !Content Section
         */
        $this->start_controls_section(
            '_content_section',
            [
                'label' => __( 'Content', 'addons-kit' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'title',
            [
                'label'       => __( 'Title', 'addons-kit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Hello World', 'addons-kit' ),
                'placeholder' => __( 'Type your title here', 'addons-kit' ),
            ]
        );
        $this->add_control(
            'desc',
            [
                'label'       => __( 'Description', 'addons-kit' ),
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 3,
                'default'     => __( 'This components based blocks are ready to use as well as customize easily.', 'addons-kit' ),
                'placeholder' => __( 'Type your Description here', 'addons-kit' ),
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            /**
             * ! Style Section
             */
            '_style_section',
            [
                'label' => __( 'Style', 'addons-kit' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $title    = $settings['title'];
        $desc     = $settings['desc'];
        /**
         * !render output
         */
        echo $title;
    }
}
