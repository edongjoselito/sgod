import 'package:flutter/foundation.dart';

import '../../../../data/models/activity_design_item.dart';
import '../../../../data/repositories/activity_designs_repository.dart';

class ActivityDesignsViewModel extends ChangeNotifier {
  ActivityDesignsViewModel(this._repo);

  final ActivityDesignsRepository _repo;

  List<ActivityDesignItem> _items = const [];
  List<ActivityDesignItem> get items => _filtered;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  String _query = '';
  String get query => _query;

  set query(String value) {
    if (_query == value) return;
    _query = value;
    notifyListeners();
  }

  List<ActivityDesignItem> get all => _items;

  List<ActivityDesignItem> get _filtered {
    final q = _query.trim().toLowerCase();
    if (q.isEmpty) return _items;
    return _items.where((m) {
      return m.title.toLowerCase().contains(q) ||
          m.activityDesignNo.toLowerCase().contains(q) ||
          m.venue.toLowerCase().contains(q);
    }).toList();
  }

  Future<void> load() async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _items = await _repo.fetch();
    } catch (e) {
      _error = '$e';
      debugPrint('ActivityDesignsViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
