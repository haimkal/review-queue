import { api } from '../api';

export class ItemService {
  static list(params) {
    return api.get('/items', { params }).then((r) => r.data);
  }

  static create(payload) {
    return api.post('/items', payload).then((r) => r.data);
  }

  static review(id, payload) {
    return api.post(`/items/${id}/review`, payload).then((r) => r.data);
  }
}
