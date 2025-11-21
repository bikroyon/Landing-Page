import { usePage } from '@inertiajs/vue3'

export function useAccess(menuKey: string) {
  const page: any = usePage()
  const sidebar = page.props.sidebar || []

  // recursive search in parent + children
  function findMenu(items: any[], key: string): any | null {
    for (const item of items) {
      if (item.slug === key) {
        return item
      }
      if (item.children && item.children.length) {
        const found = findMenu(item.children, key)
        if (found) return found
      }
    }
    return null
  }

  const menu = findMenu(sidebar, menuKey)

  function can(action: string) {
    if (!menu) return false

    // direct menu actions
    if (menu.actions && menu.actions.includes(action)) {
      return true
    }

    // also check parent actions (so parent "delete" covers child)
    if (menu.parent && menu.parent.actions && menu.parent.actions.includes(action)) {
      return true
    }

    return false
  }

  return { can }
}