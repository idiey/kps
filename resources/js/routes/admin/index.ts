import workflows from './workflows'
import templates from './templates'
import roles from './roles'
const admin = {
    workflows: Object.assign(workflows, workflows),
templates: Object.assign(templates, templates),
roles: Object.assign(roles, roles),
}

export default admin