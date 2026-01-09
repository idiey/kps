import jobs from './jobs'
import templates from './templates'
const api = {
    jobs: Object.assign(jobs, jobs),
templates: Object.assign(templates, templates),
}

export default api