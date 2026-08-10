import 'package:flutter/foundation.dart';

import '../../../../data/models/section_user_item.dart';
import '../../../../data/repositories/section_users_repository.dart';

/// Manages state for the Section Users screen.
class SectionUsersViewModel extends ChangeNotifier {
  SectionUsersViewModel(this._repo);

  final SectionUsersRepository _repo;

  List<SectionUserItem> _items = const [];
  List<SectionUserItem> get items => _items;

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  String? _error;
  String? get error => _error;

  Future<void> load() async {
    _isLoading = true;
    _error = null;
    notifyListeners();
    try {
      _items = await _repo.fetch();
    } catch (e) {
      _error = '$e';
      debugPrint('SectionUsersViewModel.load error: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
