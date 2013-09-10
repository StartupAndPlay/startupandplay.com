 <?php
                    while ($dashboard_query->have_posts()) {
                        $dashboard_query->the_post();
                        ?>
                        <tr>
                            <?php if ( 'on' == $featured_img ) { ?>
                                <td>
                                    <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( $featured_img_size );
                                    } else {
                                        printf( '<img src="%1$s" class="attachment-thumbnail wp-post-image" alt="%2$s" title="%2$s" />', apply_filters( 'wpuf_no_image', plugins_url( '/images/no-image.png', __FILE__ ) ), __( 'No Image', 'wpuf' ) );
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                            <td>
                                <?php if ( in_array( $post->post_status, array('draft', 'future', 'pending') ) ) { ?>

                                    <?php the_title(); ?>

                                <?php } else { ?>

                                    <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpuf' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>

                                <?php } ?>
                            </td>
                            <td>
                                <?php wpuf_show_post_status( $post->post_status ) ?>
                            </td>

                            <?php
                            if ( $charging_enabled == 'yes' ) {
                                $order_id = get_post_meta( $post->ID, 'wpuf_order_id', true );
                                ?>
                                <td>
                                    <?php if ( $post->post_status == 'pending' && $order_id ) { ?>
                                        <a href="<?php echo trailingslashit( get_permalink( wpuf_get_option( 'payment_page', 'wpuf_payment' ) ) ); ?>?action=wpuf_pay&type=post&post_id=<?php echo $post->ID; ?>">Pay Now</a>
                                    <?php } ?>
                                </td>
                            <?php } ?>

                            <td>
                                <?php if ( wpuf_get_option( 'enable_post_edit', 'wpuf_others', 'yes' ) == 'yes' ) { ?>
                                    <?php
                                    $edit_page = (int) wpuf_get_option( 'edit_page_id', 'wpuf_others' );
                                    $url = get_permalink( $edit_page );
                                    ?>
                                    <a href="<?php echo wp_nonce_url( $url . '?pid=' . $post->ID, 'wpuf_edit' ); ?>"><?php _e( 'Edit', 'wpuf' ); ?></a>
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>

                                <?php if ( wpuf_get_option( 'enable_post_del', 'wpuf_others', 'yes' ) == 'yes' ) { ?>
                                    <a href="<?php echo wp_nonce_url( "?action=del&pid=" . $post->ID, 'wpuf_del' ) ?>" onclick="return confirm('Are you sure to delete this post?');"><span style="color: red;"><?php _e( 'Delete', 'wpuf' ); ?></span></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="wpuf-pagination">
                <?php
                $pagination = paginate_links( array(
                    'base' => add_query_arg( 'pagenum', '%#%' ),
                    'format' => '',
                    'prev_text' => __( '&laquo;', 'wpuf' ),
                    'next_text' => __( '&raquo;', 'wpuf' ),
                    'total' => $dashboard_query->max_num_pages,
                    'current' => $pagenum
                        ) );

                if ( $pagination ) {
                    echo $pagination;
                }
                ?>
            </div>

            <?php
        } else {
            printf( __( 'No %s found', 'wpuf' ), $post_type_obj->label );
            do_action( 'wpuf_dashboard_nopost', $userdata->ID, $post_type_obj );
        }

        do_action( 'wpuf_dashboard_bottom', $userdata->ID, $post_type_obj );
        ?>

        <?php
        $this->user_info();
    }*/