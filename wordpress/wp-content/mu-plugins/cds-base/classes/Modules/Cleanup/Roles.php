<?php

namespace CDS\Modules\Cleanup;

use CDS\Utils;

class Roles
{
    public function __construct()
    {
        Utils::checkOptionCallback('cds_base_activated', '1.1.1', function () {
            if (is_blog_installed()) {
                $wp_roles = wp_roles();
                $allRoles = array_keys($wp_roles->roles); // array_keys returns only the slug

                // remove ALL roles, not just ones we know about
                foreach ($allRoles as $role) {
                    remove_role($role);
                }

                $this->cleanupRoles('gceditor');
                $this->cleanupRoles('administrator');

                // Allow Collection administrators to add new users to their Collection via the "Users → Add New" page
                update_network_option(null, 'add_new_users', 1);
            }
        });

        add_action('wpseo_activate', [$this, 'removeSEORoles'], 99);
    }

    public function removeSEORoles()
    {
        /* Installed by Yoast when plugin is activated */
        remove_role('wpseo_manager');
        remove_role('wpseo_editor');
    }

    protected function cleanupRoles($role)
    {
        $default_roles = [
            'administrator' => [
                'edit_users' => 1,
                'list_users' => 1,
                'delete_users' => 1,
                'create_users' => 1,
                'remove_users' => 1,
                'add_users' => 1,
                'promote_users' => 1,
                'manage_network_users' => 1, // enables "edit_users" for GC Admins'
                'manage_options' => 0,
                'manage_notify' => 1,
                'manage_list_manager' => 1,  // by default this is off
                'list_manager_bulk_send' => 0, // this is managed per user (see user profile)
                'manage_categories' => 1,
                'read' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'moderate_comments' => 0,
                'edit_posts' => 1,
                'edit_others_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'delete_posts' => 1,
                'delete_others_posts' => 1,
                'delete_published_posts' => 1,
                'delete_private_posts' => 1,
                'edit_private_posts' => 1,
                'read_private_posts' => 1,
                'edit_pages' => 1,
                'delete_private_pages' => 1,
                'edit_private_pages' => 1,
                'read_private_pages' => 1,
                'edit_others_pages' => 1,
                'edit_published_pages' => 1,
                'publish_pages' => 1,
                'delete_pages' => 1,
                'delete_others_pages' => 1,
                'delete_published_pages' => 1,
                'upload_files' => 1
            ],
            'editor' => [
                'moderate_comments' => 1,
                'manage_categories' => 1,
                'manage_links' => 1,
                'upload_files' => 1,
                'unfiltered_html' => 1,
                'edit_posts' => 1,
                'edit_others_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'edit_pages' => 1,
                'read' => 1,
                'level_7' => 1,
                'level_6' => 1,
                'level_5' => 1,
                'level_4' => 1,
                'level_3' => 1,
                'level_2' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'edit_others_pages' => 1,
                'edit_published_pages' => 1,
                'publish_pages' => 1,
                'delete_pages' => 1,
                'delete_others_pages' => 1,
                'delete_published_pages' => 1,
                'delete_posts' => 1,
                'delete_others_posts' => 1,
                'delete_published_posts' => 1,
                'delete_private_posts' => 1,
                'edit_private_posts' => 1,
                'read_private_posts' => 1,
                'delete_private_pages' => 1,
                'edit_private_pages' => 1,
                'read_private_pages' => 1,
            ],
            'author' => [
                'upload_files' => 1,
                'edit_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'read' => 1,
                'level_2' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'delete_posts' => 1,
                'delete_published_posts' => 1,
            ],
            'contributor' => [
                'edit_posts' => 1,
                'read' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'delete_posts' => 1,
            ],
            'subscriber' => [
                'read' => 1,
                'level_0' => 1,
            ],
            'gceditor' => [
                'read' => 1,
                'level_1' => 1,
                'level_0' => 1,
                'moderate_comments' => 0,
                'edit_posts' => 1,
                'edit_others_posts' => 1,
                'edit_published_posts' => 1,
                'publish_posts' => 1,
                'delete_posts' => 1,
                'delete_others_posts' => 1,
                'delete_published_posts' => 1,
                'delete_private_posts' => 1,
                'edit_private_posts' => 1,
                'read_private_posts' => 1,
                'edit_pages' => 1,
                'delete_private_pages' => 1,
                'edit_private_pages' => 1,
                'read_private_pages' => 1,
                'edit_others_pages' => 1,
                'edit_published_pages' => 1,
                'publish_pages' => 1,
                'delete_pages' => 1,
                'delete_others_pages' => 1,
                'delete_published_pages' => 1,
                'upload_files' => 1
            ],
            'display_name' => [
                'administrator' => 'GC Admin',
                'editor' => 'Editor',
                'author' => 'Author',
                'contributor' => 'Contributor',
                'subscriber' => 'Subscriber',
                'gceditor' => 'GC Editor',
            ],
        ];

        $role = strtolower($role);
        remove_role($role);

        return add_role(
            $role,
            $default_roles['display_name'][$role],
            $default_roles[$role],
        );
    }
}
