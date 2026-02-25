import { describe, it, expect, vi, beforeEach } from 'vitest';
import { mount } from '@vue/test-utils';
import ItemModal from '../ItemModal.vue';
import { api } from '../../api';

vi.mock('../../api', () => {
  return {
    api: {
      post: vi.fn(),
    },
  };
});

describe('ItemModal', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('posts review and emits reviewed + close on approve', async () => {
    api.post.mockResolvedValueOnce({
      data: {
        id: 7,
        title: 'Hello',
        content: 'World',
        state: 'approved',
        risk_score: 10,
        flags: [],
        review_note: 'ok',
      },
    });

    const wrapper = mount(ItemModal, {
      props: {
        open: true,
        item: {
          id: 7,
          title: 'Hello',
          content: 'World',
          state: 'pending',
          risk_score: 10,
          flags: [],
          review_note: '',
        },
      },
    });

    const approveBtn = wrapper.findAll('button').find((btn) => btn.text() === 'Approve');
    await approveBtn.trigger('click');

    expect(api.post).toHaveBeenCalledTimes(1);
    expect(api.post).toHaveBeenCalledWith('/items/7/review', {
      action: 'approve',
      note: null,
    });

    expect(wrapper.emitted('reviewed')).toBeTruthy();
    expect(wrapper.emitted('close')).toBeTruthy();
  });

  it('shows error when api fails', async () => {
    api.post.mockRejectedValueOnce({
      response: { data: { message: 'Failed to review item' } },
    });

    const wrapper = mount(ItemModal, {
      props: {
        open: true,
        item: {
          id: 7,
          title: 'Hello',
          content: 'World',
          state: 'pending',
          risk_score: 10,
          flags: [],
          review_note: '',
        },
      },
    });

    const rejectBtn = wrapper.findAll('button').find((b) => b.text() === 'Reject');
    await rejectBtn.trigger('click');

    expect(api.post).toHaveBeenCalledTimes(1);
    expect(wrapper.text()).toContain('Failed to review item');
  });
});
